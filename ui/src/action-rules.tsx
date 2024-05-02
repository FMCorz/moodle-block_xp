import { QueryClientProvider } from "@tanstack/react-query";
import React, { useCallback, useContext, useEffect, useMemo, useState } from "react";
import { createRoot } from "react-dom/client";
import { AddonRequiredShort, IfAddonPromoEnabled } from "./components/Addon";
import { Button } from "./components/Button";
import { AppLoading } from "./components/Loading";
import { DeleteModal, ModalForm } from "./components/Modal";
import { AddRuleModal } from "./components/RuleWizard";
import { RuleTypeGroup } from "./components/Rules";
import Str from "./components/Str";
import { ZeroState } from "./components/ZeroStates";
import {
  AddonContext,
  makeAddonContextValueFromAppProps,
  makeWorldContextValueFromAppProps,
  RulesSetupContext,
  WorldContext,
} from "./lib/contexts";
import { useHasReachedRuleTypeLimit, useStrings } from "./lib/hooks";
import { commonStaticModulesToDependOn, getModule, makeDependenciesDefinition } from "./lib/moodle";
import { queryClient, useAddRuleMutation, useDeleteRuleMutation, useRules } from "./lib/query";
import { useMakeRulesSetupContext, useRulesInfo } from "./lib/rules";
import { AppCommonProps, ContextLevel, RuleFilter, RuleType } from "./lib/types";
import { uniq } from "./lib/utils";

export const App = (props: AppProps) => {
  const world = useContext(WorldContext);
  const isAdmin = Boolean(world.context.id);
  const { navigateTo } = world;
  const childcontextid = props.childcontext?.id ?? null;
  const currentContext = props.childcontext ?? world.context;
  const [listedTypes, setListedTypes] = React.useState<string[]>([]);
  const [expanded, setExpanded] = React.useState<string[]>([]);
  const [isAdding, setIsAdding] = useState<boolean | string>(false);
  const [isEditing, setIsEditing] = useState<number | null>(null);
  const [isDeleting, setIsDeleting] = useState<number | null>(null);
  const [optimisticallyDeleted, setOptimisticallyDeleted] = useState<number[]>([]);
  const getStr = useStrings(["deletecondition", "editcondition", "ruleadded", "editlimits"]);

  const rulesContextData = useMakeRulesSetupContext(props);
  const { types: typesByName, filters: filtersByName } = rulesContextData;
  const rulesQuery = useRules("action", world.context.id, childcontextid);
  const hasReachedRuleLimit = useHasReachedRuleTypeLimit(rulesQuery.data);
  const { invalidateQuery } = rulesQuery;
  const { byType: rules, filtersUsageByType } = useRulesInfo(rulesQuery, optimisticallyDeleted);
  const deleteRuleMutation = useDeleteRuleMutation();
  const addRuleMutation = useAddRuleMutation(world.context.id, childcontextid, rulesContextData, {
    onSuccess: (data, variables) => {
      invalidateQuery();
      setIsAdding(false);
      setExpanded((prev) => uniq([...prev, variables.type]));
      window.location.hash = `#xp-ruletype-${variables.type}`;

      const Toast = getModule("core/toast");
      Toast && Toast.add(getStr("ruleadded"));
    },
  });

  const sortedTypes = useMemo(() => {
    const typeNames = hasReachedRuleLimit ? uniq([...rules.keys()]) : listedTypes;
    return (typeNames.map((typeName) => typesByName.get(typeName)).filter(Boolean) as RuleType[]).sort((a, b) => {
      return a.label.localeCompare(b.label);
    });
  }, [hasReachedRuleLimit, listedTypes, rules, typesByName]);

  // Keep a list of all the types we've seen, that is to avoid for a type to disappear when its last condition is deleted.
  useEffect(() => {
    setListedTypes((types) => uniq([...types, ...(rules ? rules.keys() : [])]));
  }, [rules]);

  const handleDelete = useCallback(() => {
    if (!isDeleting) return;
    setOptimisticallyDeleted([...optimisticallyDeleted, isDeleting]);
    deleteRuleMutation.mutate(
      { id: isDeleting },
      {
        onError: () => {
          setOptimisticallyDeleted(optimisticallyDeleted.filter((id) => id !== isDeleting));
        },
        onSuccess: () => {
          invalidateQuery();
        },
        onSettled: () => {
          setIsDeleting(null);
        },
      },
    );
  }, [isDeleting, optimisticallyDeleted, deleteRuleMutation, invalidateQuery]);

  const toggleExpanded = useCallback((name: string) => {
    setExpanded((prev) => {
      if (prev.includes(name)) {
        return prev.filter((n) => n !== name);
      }
      return [...prev, name];
    });
  }, []);

  const handleAddAction = useCallback(() => setIsAdding(true), []);
  const handleIsAdding = useCallback((type?: string) => setIsAdding(type || true), []);
  const handleViewRuleLogs = useCallback((ruleId: number) => navigateTo("log", { ruleid: ruleId }), [navigateTo]);

  if (rulesQuery.isLoading || rulesQuery.isError) {
    return <AppLoading />;
  }

  const hasNoContent = !sortedTypes.length;

  return (
    <RulesSetupContext.Provider
      value={{
        context: currentContext,
        types: rulesContextData.types,
        filters: rulesContextData.filters,
        filtersUsageByType,
        addRule: handleIsAdding,
        editRule: setIsEditing,
        removeRule: setIsDeleting,
        viewRuleLogs: isAdmin ? handleViewRuleLogs : undefined,
      }}
    >
      {hasNoContent ? (
        <ZeroState title={<Str id="noactionsyet" />} intro={<Str id="noactionsyetintro" />}>
          <button className="btn btn-primary" onClick={handleAddAction}>
            <Str id="addaction" />
          </button>
        </ZeroState>
      ) : (
        <>
          <div className="xp-flex xp-justify-end xp-mb-4 xp-gap-2 xp-items-center">
            <IfAddonPromoEnabled>
              {hasReachedRuleLimit ? (
                <>
                  <div className="xp-text-sm xp-text-gray-700 xp-leading-none">
                    <Str id="upgradetoaddmore" />
                  </div>
                  <div className="xp-leading-none">
                    <AddonRequiredShort />
                  </div>
                </>
              ) : null}
            </IfAddonPromoEnabled>
            <Button className="btn-sm" primary onClick={handleAddAction} disabled={hasReachedRuleLimit}>
              <Str id="addaction" />
            </Button>
          </div>
          <div className="xp-space-y-4">
            {sortedTypes.map((type) => {
              const typeRules = rules.get(type.name) ?? [];
              return (
                <RuleTypeGroup
                  key={type.name}
                  type={type}
                  filters={filtersByName}
                  rules={typeRules}
                  expanded={expanded.includes(type.name)}
                  onExpanded={() => toggleExpanded(type.name)}
                />
              );
            })}
          </div>
          <DeleteModal
            show={isDeleting !== null}
            onClose={() => setIsDeleting(null)}
            onDelete={handleDelete}
            title={getStr("deletecondition")}
          >
            <Str id="areyousure" component="core" />
          </DeleteModal>
          {isEditing ? (
            <ModalForm
              formClass={props.ruleformclass}
              formArgs={{ id: isEditing }}
              title={getStr("editcondition")}
              onClose={() => setIsEditing(null)}
              onSubmit={() => {
                setIsEditing(null);
                invalidateQuery();
              }}
            />
          ) : null}
        </>
      )}
      <AddRuleModal
        show={Boolean(isAdding)}
        selectedType={typeof isAdding === "string" ? isAdding : undefined}
        onClose={() => setIsAdding(false)}
        onSave={({ type, filter, ...config }) => {
          addRuleMutation.mutate({ type, filter, ...config });
        }}
      />
    </RulesSetupContext.Provider>
  );
};

type AppProps = AppCommonProps & {
  childcontext: null | {
    id: number;
    contextlevel: ContextLevel;
    instanceid: number;
  };
  rulefilters: RuleFilter[];
  ruleformclass: string;
  ruletypes: RuleType[];
};

function startApp(node: HTMLElement, props: AppProps) {
  const root = createRoot(node);
  root.render(
    <WorldContext.Provider value={makeWorldContextValueFromAppProps(props)}>
      <AddonContext.Provider value={makeAddonContextValueFromAppProps(props)}>
        <QueryClientProvider client={queryClient}>
          <App {...props} />
        </QueryClientProvider>
      </AddonContext.Provider>
    </WorldContext.Provider>,
  );
}

const dependencies = makeDependenciesDefinition(commonStaticModulesToDependOn);

export { dependencies, startApp };
