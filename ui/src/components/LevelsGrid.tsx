import React from 'react';
import { useString, useStrings } from '../lib/hooks';
import { getMinimumPointsForLevel } from '../lib/levels';
import { Level as LevelType } from '../lib/types';
import Input from './Input';
import Level from './Level';
import NumberInput from './NumberInput';
import Str from './Str';

const LevelsGrid: React.FC<{
  levels: LevelType[];
  algoEnabled: boolean;
  onDescChange: (level: LevelType, desc: string | null) => void;
  onNameChange: (level: LevelType, name: string | null) => void;
  onPointsChange: (level: LevelType, n: number | null) => void;
}> = ({ algoEnabled, levels, onDescChange, onNameChange, onPointsChange }) => {
  return (
    <div className="xp-flex xp-flex-wrap xp--ml-4">
      {levels.map((level) => {
        return (
          <LevelTile
            key={level.level}
            level={level}
            minPoints={getMinimumPointsForLevel(levels, level)}
            pointsEditable={level.level > 1 && !algoEnabled}
            onDescChange={(nb) => onDescChange(level, nb)}
            onNameChange={(nb) => onNameChange(level, nb)}
            onPointsChange={(nb) => onPointsChange(level, nb)}
          />
        );
      })}
    </div>
  );
};

const Field: React.FC<{ level: LevelType; label: string }> = ({ level, label, children }) => {
  return (
    <label className="xp-m-0 xp-font-normal">
      <div className="">
        <div className="xp-sr-only">
          <Str id="leveln" a={level.level} />
        </div>
        {label}
      </div>
      {children}
    </label>
  );
};

const LevelTile: React.FC<{
  level: LevelType;
  minPoints: number;
  pointsEditable: boolean;
  onDescChange: (desc: string | null) => void;
  onNameChange: (name: string | null) => void;
  onPointsChange: (points: number | null) => void;
}> = ({ minPoints, level, pointsEditable, onPointsChange, onNameChange, onDescChange }) => {
  const getStr = useStrings(['noname', 'nodescription', 'pointsrequired', 'description', 'name']);
  const leveln = useString('leveln', 'block_xp', level.level);
  return (
    <div className="xp-flex-none md:xp-w-1/3 lg:xp-w-1/4 xp-w-1/2 xp-pl-4 xp-mb-4">
      <div className="xp-bg-gray-100 xp-p-4 xp-pt-2 xp-rounded">
        <div className="xp-flex xp-flex-col xp-items-center">
          <div>
            <div className="xp-sr-only">{leveln}</div>
            <Level level={level} />
          </div>
          <div className="xp-w-full xp-mb-2">
            <Field level={level} label={getStr('name')}>
              <Input
                className="xp-w-full"
                placeholder={getStr('noname')}
                onChange={(e) => onNameChange(e.target.value || null)}
                defaultValue={level.name || ''}
                maxLength={40}
                type="text"
              />
            </Field>
          </div>
          <div className="xp-w-full xp-mb-2 xp-break-all">
            <Field level={level} label={getStr('pointsrequired')}>
              <NumberInput
                min={minPoints}
                onChange={onPointsChange}
                value={level.xprequired}
                size={5}
                disabled={!pointsEditable}
                className="xp-px-2 xp-py-1 xp-w-28"
              />
            </Field>
          </div>
          <div className="xp-w-full">
            <Field level={level} label={getStr('description')}>
              <Input
                className="xp-w-full"
                placeholder={getStr('nodescription')}
                onChange={(e) => onDescChange(e.target.value || null)}
                defaultValue={level.description || ''}
                maxLength={255}
                type="text"
              />
            </Field>
          </div>
        </div>
      </div>
    </div>
  );
};

export default LevelsGrid;
