import React, { useEffect, useReducer } from 'react';
import ReactDOM from 'react-dom';
import { QueryClient, QueryClientProvider, useMutation } from 'react-query';
import { SaveButton } from './components/Button';
import LevelsGrid from './components/LevelsGrid';
import LevelsList from './components/LevelsList';
import NumberInput from './components/NumberInput';
import Str from './components/Str';
import { useStrings, useUnloadCheck } from './lib/hooks';
import {
  computeRequiredPoints,
  getMinimumPointsAtLevel,
  getMinimumPointsForLevel,
  getNextLevel,
  getPreviousLevel,
} from './lib/levels';
import { getModule, makeDependenciesDefinition } from './lib/moodle';
import { Level as LevelType, LevelsInfo } from './lib/types';
import { stripTags } from './lib/utils';

type State = {
  algo: LevelsInfo['algo'];
  levels: (LevelType & { hasFile: boolean })[];
  nblevels: number;
  view: 'grid' | 'list';
  pendingSave: boolean;
};

const markPendingSave = (state: State): State => {
  return { ...state, pendingSave: true };
};

const updateLevelPoints = (state: State): State => {
  if (!state.algo.enabled) {
    return {
      ...state,
      levels: state.levels.reduce<State['levels']>((carry, level, i) => {
        return carry.concat([
          { ...level, xprequired: Math.max(level.xprequired, getMinimumPointsForLevel(carry.concat([level]), level)) },
        ]);
      }, []),
    };
  }
  return {
    ...state,
    levels: state.levels.map((level) => {
      return { ...level, xprequired: computeRequiredPoints(level.level, state.algo.base, state.algo.coef) };
    }),
  };
};

const reducer = (state: State, [action, payload]: [string, any]): State => {
  let nextLevel;
  switch (action) {
    case 'algoBaseChange':
      if (!payload || payload < 1 || isNaN(payload)) return state;
      const base = Math.min(999999, Math.max(payload, 1));
      return markPendingSave(
        updateLevelPoints({
          ...state,
          algo: {
            ...state.algo,
            base: base,
          },
        })
      );
    case 'algoCoefChange':
      if (!payload || payload < 1 || isNaN(payload)) return state;
      return markPendingSave(
        updateLevelPoints({
          ...state,
          algo: {
            ...state.algo,
            coef: Math.min(5, Math.max(1, payload)),
          },
        })
      );
    case 'algoEnabledChange':
      return markPendingSave(
        updateLevelPoints({
          ...state,
          algo: {
            ...state.algo,
            enabled: Boolean(payload),
          },
        })
      );

    case 'levelDescChange':
      return markPendingSave({
        ...state,
        levels: state.levels.map((level) => {
          if (level !== payload.level) {
            return level;
          }
          return { ...level, description: payload.desc || null };
        }),
      });
    case 'levelNameChange':
      return markPendingSave({
        ...state,
        levels: state.levels.map((level) => {
          if (level !== payload.level) {
            return level;
          }
          return { ...level, name: payload.name || null };
        }),
      });
    case 'levelPointsChange':
      nextLevel = getNextLevel(state.levels, payload.level, state.nblevels);
      if (isNaN(payload.points) || payload.points <= 2 || payload.points >= Infinity) {
        return state;
      } else if (payload.points <= getPreviousLevel(state.levels, payload.level).xprequired) {
        return state;
      }
      return markPendingSave(
        updateLevelPoints({
          ...state,
          levels: state.levels.map((level) => {
            if (level !== payload.level) {
              return level;
            }
            return { ...level, xprequired: payload.points };
          }),
        })
      );
    case 'nbLevelsChange':
      if (isNaN(payload) || payload < 2 || payload > 99) {
        return state;
      }
      return markPendingSave({
        ...state,
        nblevels: payload,
        levels: state.levels.concat(
          Array.from({ length: Math.max(0, payload - state.levels.length) }).map((_, i) => {
            const l = state.levels.length + i + 1;
            return {
              level: l,
              name: null,
              description: null,
              badgeurl: null,
              xprequired: state.algo.enabled
                ? computeRequiredPoints(l, state.algo.base, state.algo.coef)
                : getMinimumPointsAtLevel(state.levels, l),
              hasFile: false,
            };
          })
        ),
      });
    case 'markSaved':
      return {
        ...state,
        pendingSave: false,
      };
    case 'setView':
      return {
        ...state,
        view: payload,
      };
  }
  return state;
};

const initState = ({ levelsInfo }: { levelsInfo: LevelsInfo }): State => {
  return {
    algo: { ...levelsInfo.algo },
    levels: levelsInfo.levels.map((level) => {
      return {
        ...level,
        hasFile: false, // levelsWithFile ? levelsWithFile.indexOf(level.level) > -1 : level.hasFile
      };
    }),
    nblevels: levelsInfo.count,
    view: 'grid',
    pendingSave: false,
  };
};

const App = ({ courseId, levelsInfo }: { courseId: number; levelsInfo: LevelsInfo }) => {
  const [state, dispatch] = useReducer(reducer, { levelsInfo }, initState);
  const levels = state.levels.slice(0, state.nblevels);
  useUnloadCheck(state.pendingSave);
  const mutation = useMutation(
    () => {
      // An falsy course ID means admin config.
      let method = courseId ? 'block_xp_set_levels_info' : 'block_xp_set_default_levels_info';
      return getModule('core/ajax').call([
        {
          methodname: method,
          args: {
            courseid: courseId ? courseId : undefined,
            levels: levels.map((level) => {
              return {
                level: level.level,
                xprequired: level.xprequired,
                name: stripTags(level.name || ''),
                description: stripTags(level.description || ''),
              };
            }),
            algo: state.algo,
          },
        },
      ])[0];
    },
    {
      onSuccess: () => {
        dispatch(['markSaved', true]);
      },
    }
  );

  // Reset mutation after success.
  useEffect(() => {
    if (!mutation.isSuccess) {
      return;
    }
    const t = setTimeout(() => {
      mutation.reset();
    }, 2500);
    return () => clearTimeout(t);
  });

  const handleSave = () => {
    mutation.mutate();
  };

  const rendererProps = {
    levels: levels,
    algoEnabled: state.algo.enabled,
    onDescChange: (level: LevelType, desc: string | null) => dispatch(['levelDescChange', { level: level, desc }]),
    onNameChange: (level: LevelType, name: string | null) => dispatch(['levelNameChange', { level: level, name }]),
    onPointsChange: (level: LevelType, nb: number | null) => dispatch(['levelPointsChange', { level: level, points: nb }]),
  };
  const Renderer = state.view === 'grid' ? LevelsGrid : LevelsList;

  return (
    <div>
      <div className="xp-flex xp-flex-col xp-flex-wrap">
        <div className="xp-mb-4">
          <Menu
            algo={state.algo}
            nbLevels={state.nblevels}
            canSave={state.pendingSave}
            view={state.view}
            mutation={mutation}
            onAlgoEnabledChange={(p: boolean) => dispatch(['algoEnabledChange', p])}
            onAlgoBaseChange={(p: number | null) => dispatch(['algoBaseChange', p])}
            onAlgoCoefChange={(p: number | null) => dispatch(['algoCoefChange', p])}
            onNbLevelsChange={(nb) => dispatch(['nbLevelsChange', nb])}
            onSave={handleSave}
            onViewChange={(v) => dispatch(['setView', v])}
          />
        </div>
        <div className="xp-flex-1">
          <Renderer {...rendererProps} />
        </div>
      </div>
    </div>
  );
};

const MenuItem: React.FC<{ label?: React.ReactNode; className?: string }> = ({ label, className = '', children }) => {
  return (
    <div className={`xp-mb-2 xp-mr-2 ${className}`}>
      {label ? <div>{label}</div> : null}
      <div>{children}</div>
    </div>
  );
};

const Menu: React.FC<{
  nbLevels: number;
  canSave: boolean;
  view: State['view'];
  algo: State['algo'];
  mutation?: any;
  onNbLevelsChange: (nb: number | null) => void;
  onAlgoEnabledChange: (enabled: boolean) => void;
  onAlgoCoefChange: (n: number | null) => void;
  onAlgoBaseChange: (n: number | null) => void;
  onViewChange: (v: State['view']) => void;
  onSave: () => void;
}> = ({
  algo,
  canSave,
  view,
  mutation,
  nbLevels,
  onAlgoEnabledChange,
  onAlgoBaseChange,
  onAlgoCoefChange,
  onNbLevelsChange,
  onViewChange,
  onSave,
}) => {
  const getStr = useStrings(['grid', 'list', 'usingalgo', 'manually']);
  return (
    <div className="xp-flex xp-flex-col md:xp-flex-row">
      <div className="xp-flex xp-flex-col xp-flex-wrap xp-order-2 md:xp-order-none md:xp-flex-row">
        <MenuItem
          label={
            <label htmlFor="bxpViewAs" className="xp-m-0">
              <Str id="viewas" />
            </label>
          }
        >
          <select
            id="bxpViewAs"
            value={view}
            onChange={(e) => onViewChange(e.target.value as 'list' | 'grid')}
            className="form-control xp-m-0 xp-max-w-xs"
          >
            <option value="grid">{getStr('grid')}</option>
            <option value="list">{getStr('list')}</option>
          </select>
        </MenuItem>

        <MenuItem
          label={
            <label htmlFor="bxpNbLevels" className="xp-m-0">
              <Str id="levelcount" />
            </label>
          }
        >
          <NumberInput
            id="bxpNbLevels"
            min={2}
            max={99}
            size={4}
            onChange={onNbLevelsChange}
            value={nbLevels}
            className="form-control"
          />
        </MenuItem>

        <MenuItem
          label={
            <label htmlFor="bxpAlgoEnabled" className="xp-m-0">
              <Str id="setpoints" />
            </label>
          }
        >
          <select
            id="bxpAlgoEnabled"
            value={algo.enabled ? 'usealgo' : ''}
            onChange={(e) => onAlgoEnabledChange(Boolean(e.target.value))}
            className="form-control xp-m-0 xp-max-w-xs"
          >
            <option value="usealgo">{getStr('usingalgo')}</option>
            <option value="">{getStr('manually')}</option>
          </select>
        </MenuItem>

        {algo.enabled ? (
          <>
            <MenuItem
              label={
                <label htmlFor="bxpAlgoPts" className="xp-m-0">
                  <Str id="basexp" />
                </label>
              }
            >
              <NumberInput
                id="bxpAlgoPts"
                min={1}
                size={4}
                disabled={!algo.enabled}
                value={algo.base}
                onChange={onAlgoBaseChange}
              />
            </MenuItem>

            <MenuItem
              label={
                <label htmlFor="bxpAlgoCoef" className="xp-m-0">
                  <Str id="coefxp" />
                </label>
              }
            >
              <NumberInput
                id="bxpAlgoCoef"
                min={1}
                max={5}
                size={4}
                step={0.1}
                disabled={!algo.enabled}
                value={algo.coef}
                onChange={onAlgoCoefChange}
              />
            </MenuItem>
          </>
        ) : null}
      </div>

      <div className="xp-flex-1 xp-flex md:xp-justify-end">
        <MenuItem className="md:xp-hidden">
          <SaveButton statePosition="after" onClick={onSave} mutation={mutation} disabled={!canSave} />
        </MenuItem>
        <MenuItem className="xp-hidden md:xp-block">
          <SaveButton statePosition="before" onClick={onSave} mutation={mutation} disabled={!canSave} />
        </MenuItem>
      </div>
    </div>
  );
};

const queryClient = new QueryClient({
  defaultOptions: {
    mutations: {
      onError: (err) => getModule('core/notification').exception(err),
    },
  },
});

function startApp(node: HTMLElement, props: any) {
  ReactDOM.render(
    <QueryClientProvider client={queryClient}>
      <App {...props} />
    </QueryClientProvider>,
    node
  );
}

const dependencies = makeDependenciesDefinition(['core/str', 'core/ajax', 'core/notification']);

export { dependencies, startApp };
