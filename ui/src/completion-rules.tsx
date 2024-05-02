import { Tab } from "@headlessui/react";
import React, { Fragment, useCallback, useContext, useMemo, useState } from "react";
import { createRoot } from "react-dom/client";
import { QueryClientProvider } from "@tanstack/react-query";
import { AppLoading } from "./components/Loading";
import { DeleteModal, ModalForm } from "./components/Modal";
import { NotificationError } from "./components/Notification";
import { AddRuleModal } from "./components/RuleWizard";
import { RuleEntry, RulesSection, RulesSectionGroup } from "./components/Rules";
import Str from "./components/Str";
import { ZeroState } from "./components/ZeroStates";
import {
  AddonContext,
  RulesSetupContext,
  WorldContext,
  makeAddonContextValueFromAppProps,
  makeWorldContextValueFromAppProps,
} from "./lib/contexts";
import { useStrings } from "./lib/hooks";
import { commonStaticModulesToDependOn, getModule, getUrl, makeDependenciesDefinition } from "./lib/moodle";
import { queryClient, useAddRuleMutation, useDeleteRuleMutation, useRules } from "./lib/query";
import { useMakeRulesSetupContext, useRulesInfo } from "./lib/rules";
import { AppCommonProps, ContextLevel, Rule, RuleFilter, RuleType, RuleV2 } from "./lib/types";
import { classNames, groupBy } from "./lib/utils";

const availableRuleTypes = ["cm_completion", "section_completion", "course_completion"];
const guessMethodFromLocation = () => {
  return Math.max(0, availableRuleTypes.indexOf((window.location.hash ?? "").replace(/^#/, "")));
};
const updateLocationFromMethodIndex = (idx: number) => {
  const hash = "#" + availableRuleTypes[idx];
  window.location.hash = hash;
};

export const App = (props: AppProps) => {
  const world = useContext(WorldContext);
  const { navigateTo } = world;
  const currentContext = props.childcontext ?? world.context;
  const rulesContextData = useMakeRulesSetupContext(props);
  const [selectedTabIndex, setSelectedTabIndex] = useState(guessMethodFromLocation);
  const [optimisticallyDeleted, setOptimisticallyDeleted] = useState<number[]>([]);
  const childcontextid = props.childcontext?.id ?? null;

  const [isAdding, setIsAdding] = useState(false);
  const [isEditing, setIsEditing] = useState<number | null>(null);
  const [isDeleting, setIsDeleting] = useState<number | null>(null);

  const getStr = useStrings(["deletecondition", "editcondition", "ruleadded"]);
  const rulesQuery = useRules("completion", world.context.id, childcontextid);
  const { invalidateQuery } = rulesQuery;
  const { byType: rules, filtersUsageByType } = useRulesInfo(rulesQuery, optimisticallyDeleted);
  const addRuleMutation = useAddRuleMutation(world.context.id, childcontextid, rulesContextData, {
    onSuccess: () => {
      invalidateQuery();
      setIsAdding(false);
      const Toast = getModule("core/toast");
      Toast && Toast.add(getStr("ruleadded"));
    },
  });
  const deleteRuleMutation = useDeleteRuleMutation();

  const handleSelectedTabIndexChange = (idx: number) => {
    setSelectedTabIndex(idx);
    setIsAdding(false);
    setIsEditing(null);
    setIsDeleting(null);
    updateLocationFromMethodIndex(idx);
  };
  const handleViewRuleLogs = useCallback((ruleId: number) => navigateTo("log", { ruleid: ruleId }), [navigateTo]);

  const currentRuleType = availableRuleTypes[selectedTabIndex];
  const typesByName = rulesContextData.types;

  const canAdd = true;
  const showAddBtnInTabs = canAdd && rules.get(currentRuleType)?.length;

  if (rulesQuery.isLoading || rulesQuery.isError) {
    return <AppLoading />;
  }

  return (
    <RulesSetupContext.Provider
      value={{
        addRule: () => setIsAdding(true),
        editRule: (ruleId: number) => setIsEditing(ruleId),
        removeRule: (ruleId: number) => setIsDeleting(ruleId),
        viewRuleLogs: handleViewRuleLogs,
        context: currentContext,
        filters: rulesContextData.filters,
        filtersUsageByType: filtersUsageByType,
        types: rulesContextData.types,
      }}
    >
      <div>
        <Tab.Group selectedIndex={selectedTabIndex} onChange={handleSelectedTabIndexChange}>
          <Tab.List as="div" className="nav nav-tabs">
            <Tab as={Fragment}>
              {({ selected }) => (
                <button className={classNames("nav-item nav-link", selected ? "active" : null)}>
                  <Str id="activity" component="core" />
                </button>
              )}
            </Tab>
            <Tab as={Fragment}>
              {({ selected }) => (
                <button className={classNames("nav-item nav-link", selected ? "active" : null)}>
                  <Str id="section" component="core" />
                </button>
              )}
            </Tab>
            <Tab as={Fragment}>
              {({ selected }) => (
                <button className={classNames("nav-item nav-link", selected ? "active" : null)}>
                  <Str id="course" component="core" />
                </button>
              )}
            </Tab>
            <div className="xp-flex-1 xp-flex xp-justify-end xp-items-center">
              {showAddBtnInTabs ? (
                <button className="btn btn-primary btn-sm" onClick={() => setIsAdding(true)}>
                  <Str id="add" component="core" />
                </button>
              ) : null}
            </div>
          </Tab.List>
          <Tab.Panels className="xp-mt-4">
            <Tab.Panel>
              {typesByName.has("cm_completion") ? (
                <CompletionRules rules={rules.get("cm_completion") ?? []} type={typesByName.get("cm_completion")!} />
              ) : (
                <NotificationError>
                  <Str id="unknowntypea" a="cm_completion" />
                </NotificationError>
              )}
            </Tab.Panel>
            <Tab.Panel>
              {typesByName.has("section_completion") ? (
                <CompletionRules rules={rules.get("section_completion") ?? []} type={typesByName.get("section_completion")!} />
              ) : (
                <NotificationError>
                  <Str id="unknowntype" a="section_completion" />
                </NotificationError>
              )}
            </Tab.Panel>
            <Tab.Panel>
              {typesByName.has("course_completion") ? (
                <CompletionRules rules={rules.get("course_completion") ?? []} type={typesByName.get("course_completion")!} />
              ) : (
                <NotificationError>
                  <Str id="unknowntype" a="course_completion" />
                </NotificationError>
              )}
            </Tab.Panel>
          </Tab.Panels>
        </Tab.Group>
      </div>
      <AddRuleModal
        show={isAdding}
        selectedType={currentRuleType}
        onClose={() => setIsAdding(false)}
        onSave={({ filter, ...config }) => {
          addRuleMutation.mutate({ type: currentRuleType, filter, ...config });
        }}
      />
      <DeleteModal
        show={isDeleting !== null}
        onClose={() => setIsDeleting(null)}
        onDelete={() => {
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
        }}
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
    </RulesSetupContext.Provider>
  );
};
const NoRulesZeroState = ({ onClick }: { onClick: () => void }) => {
  return (
    <ZeroState title={<Str id="noconditionsyet" />} intro={<Str id="noconditionsyetintro" />}>
      <button className="btn btn-primary" onClick={onClick}>
        <Str id="add" component="core" />
      </button>
    </ZeroState>
  );
};

const CompletionRules = ({ rules: rulesV2, type }: { rules?: RuleV2[]; type: RuleType }) => {
  const { addRule, removeRule, editRule, filters, viewRuleLogs } = useContext(RulesSetupContext);
  const rules: Rule[] = useMemo(() => {
    return (
      rulesV2?.map((rule) => ({
        ...rule,
        filter: rule.filtername,
        method: rule.typename,
      })) ?? []
    );
  }, [rulesV2]);

  const filteredRules = useMemo(
    () => rules?.filter((r) => type.filters.includes(r.filter) && filters.has(r.filter)),
    [rules, type.filters, filters],
  );
  const groupedRules = useMemo(() => Array.from(groupBy(filteredRules, "filter")), [filteredRules]);
  const handleAddClick = () => {
    addRule();
  };

  if (!filteredRules?.length) {
    return <NoRulesZeroState onClick={handleAddClick} />;
  }

  return (
    <RulesSectionGroup>
      {groupedRules.map(([filter, rules]) => {
        const ruleFilter = filters.get(filter);
        if (!ruleFilter) return null;
        return (
          <RulesSection key={filter} title={ruleFilter?.label} description={ruleFilter?.description}>
            {rules.map((rule) => {
              return (
                <RuleEntry
                  key={rule.id}
                  rule={rule}
                  onDelete={() => removeRule(rule.id)}
                  onEdit={() => editRule(rule.id)}
                  onViewLogs={viewRuleLogs ? () => viewRuleLogs(rule.id) : undefined}
                />
              );
            })}
          </RulesSection>
        );
      })}
    </RulesSectionGroup>
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
