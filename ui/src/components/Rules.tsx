import React, { useContext, useMemo } from "react";
import { useAddonActivated, useAnchorButtonProps, useRuleCreationLimit, useStrings, useUniqueId } from "../lib/hooks";
import { getTypeThemeColor } from "../lib/rules";
import { Icon, LimitSpec, LimitSpecTimeWindow, Rule, RuleFilter, RuleType, RuleV2 } from "../lib/types";
import { classNames, groupBy } from "../lib/utils";
import { AddonRequiredShort, IfAddonPromoEnabled } from "./Addon";
import { Button, ExpandCollapseButton } from "./Button";
import { Dropdown } from "./Dropdown";
import Expandable from "./Expandable";
import { IconRenderer } from "./Icons";
import Str from "./Str";
import { RulesSetupContext } from "../lib/contexts";
import { ruleTypeSupportsLimits } from "../lib/rulelimits";
import { Tooltip } from "./Tooltip";

export const RuleTypeIcon = ({ themeColor, icon }: { themeColor?: [string, string]; icon: Icon }) => {
  const [themeFgColor, themeBgColor] = themeColor || [];
  return (
    <div
      className={classNames(
        "xp-w-14 xp-h-14 xp-rounded-lg xp-flex xp-text-center xp-items-center xp-justify-center",
        "xp-text-white xp-bg-indigo-500 xp-text-2xl xp-leading-none",
      )}
      style={{ color: themeFgColor, background: themeBgColor }}
    >
      <IconRenderer icon={icon} />
    </div>
  );
};

export const RuleTypeStatsList = ({ children }: { children: React.ReactNode[] }) => {
  return <dl className="xp-m-0 xp-flex xp-leading-none xp-gap-4">{children}</dl>;
};

export const RuleTypeStat = ({ label, value }: { label: React.ReactNode; value: React.ReactNode }) => {
  return (
    <div className="xp-flex-1 xp-shrink-0">
      <dt className="xp-m-0 xp-font-normal xp-uppercase xp-text-2xs xp-text-gray-500">{label}</dt>
      <dd className="xp-m-0 xp-font-normal xp-text xp-text-sm xp-whitespace-nowrap xp-truncate">{value}</dd>
    </div>
  );
};

export const RuleTypeGroup = ({
  type,
  rules,
  filters,
  expanded,
  onExpanded,
}: {
  type: RuleType;
  rules: RuleV2[];
  filters: Map<string, RuleFilter>;
  expanded: boolean;
  onExpanded: () => void;
}) => {
  const expandableId = useUniqueId();
  const filterLimit = useRuleCreationLimit();
  const { isEmpty, pointsMin, pointsMax, nRules, rulesByFilter } = useMemo(() => {
    const pointsList = rules.map((r) => r.points);
    const pointsMin = Math.min(...pointsList);
    const pointsMax = Math.max(...pointsList);
    return {
      isEmpty: rules.length === 0,
      pointsMin,
      pointsMax,
      nRules: rules.length,
      rulesByFilter: Array.from(groupBy(rules, "filtername")),
    };
  }, [rules]);

  const { addRule, editRule, removeRule, viewRuleLogs } = useContext(RulesSetupContext);

  const hasReachedFilterLimit = useMemo(() => {
    return filterLimit > 0 && rules.length >= filterLimit;
  }, [filterLimit, rules]);

  return (
    <div className="xp-rounded-lg xp-border xp-border-solid xp-border-gray-200 xp-p-3" id={`xp-ruletype-${type.name}`}>
      <div className="xp-flex xp-items-center xp-gap-2">
        <div className="xp-grow xp-flex xp-gap-3">
          {type.icon ? (
            <div className="xp-shrink-0 xp-grow-0">
              <RuleTypeIcon themeColor={getTypeThemeColor(type)} icon={type.icon} />
            </div>
          ) : null}
          <div className="xp-grow">
            <h4 className="xp-text-lg xp-leading-none xp-m-0">{type.label}</h4>
            <div className="xp-mt-2">
              <RuleTypeStatsList>
                <RuleTypeStat
                  label={<Str id="points" />}
                  value={isEmpty ? "-" : pointsMin === pointsMax ? pointsMin : `${pointsMin} – ${pointsMax}`}
                />
                <RuleTypeStat label={<Str id="conditions" />} value={nRules} />
              </RuleTypeStatsList>
            </div>
          </div>
        </div>
        <div className="xp-grow-0 xp-shrink-0">
          <ExpandCollapseButton ariaControlsId={expandableId} expanded={expanded} onToggle={onExpanded} />
        </div>
      </div>
      <Expandable expanded={expanded} id={expandableId}>
        {isEmpty ? (
          <div className="xp-pt-3">
            <div className="xp-rounded xp-border-dashed xp-border-2 xp-p-4 xp-py-6 xp-text-center xp-border-gray-200">
              <div className="xp-text-xl xp-font-bold xp-mb-4">
                <Str id="noconditionsyet" />
              </div>
              <div>
                <Str id="noconditionsyetintro" />
              </div>
              <div className="xp-mt-4">
                <button className="btn btn-primary" onClick={() => addRule(type.name)}>
                  <Str id="addcondition" />
                </button>
              </div>
            </div>
          </div>
        ) : null}
        {!isEmpty ? (
          <div className="xp-pt-2">
            <div className="xp-flex xp-my-2">
              <div className="xp-grow"></div>
              <div className="xp-shrink-0 xp-flex xp-gap-2 xp-items-center">
                <IfAddonPromoEnabled>
                  {hasReachedFilterLimit ? (
                    <>
                      <div className="xp-text-sm xp-text-gray-700 xp-leading-none">
                        <Str id="upgradetoaddmore" />
                      </div>
                      <AddonRequiredShort />
                    </>
                  ) : null}
                </IfAddonPromoEnabled>
                <Button className="btn-sm" primary outline onClick={() => addRule(type.name)} disabled={hasReachedFilterLimit}>
                  <Str id="addcondition" />
                </Button>
              </div>
            </div>
            <RulesSectionGroup enclosed>
              {rulesByFilter.map(([filterName, rules]) => {
                const filter = filters.get(filterName);
                if (!filter) return null;
                return (
                  <RulesSection key={filterName} title={filter.label} description={filter.description}>
                    {rules.map((rule) => {
                      return (
                        <RuleEntry
                          key={rule.id}
                          rule={rule}
                          type={type}
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
          </div>
        ) : null}
      </Expandable>
    </div>
  );
};

export const RulesSectionGroup = ({ children, enclosed }: { children: React.ReactNode; enclosed?: boolean }) => {
  return (
    <div
      className={classNames(
        "xp-space-y-4",
        /* When the group is enclosed (it has borders around it) we remove the border of the very last entry. */
        enclosed ? "[&_.xp-rules-section:last-child_.xp-rule-entry:last-child]:xp-border-0" : null,
      )}
    >
      {children}
    </div>
  );
};

export const RulesSection = ({
  children,
  title,
  description,
}: {
  children: React.ReactNode;
  title: React.ReactNode;
  description: React.ReactNode;
}) => {
  return (
    <div className="xp-rules-section">
      <h5 className="xp-font-bold xp-m-0 xp-mb-1 xp-text-base">{title}</h5>
      <p className="xp-mb-2 xp-text-sm xp-text-gray-500 xp-m-0">{description}</p>
      <div className="[&>div]:xp-border-0 [&>div]:xp-border-b [&>div]:xp-border-solid [&>div]:xp-border-gray-200">{children}</div>
    </div>
  );
};

export const RuleEntry = ({
  rule,
  type,
  onDelete,
  onEdit,
  onViewLogs,
  extra,
}: {
  rule: Rule | RuleV2;
  type?: RuleType;
  onDelete: () => void;
  onEdit: () => void;
  onViewLogs?: () => void;
  extra?: React.ReactNode;
}) => {
  const { points, label } = rule;
  return (
    <div className="xp-rule-entry">
      <div className="xp-flex xp-gap-2 xp-py-1">
        <div className="xp-shrink-0">
          <div
            className={classNames(
              "xp-min-w-[86px] xp-min-h-7 xp-flex xp-items-center xp-text-center xp-justify-center xp-rounded",
              "xp-px-2 xp-py-0.5 xp-font-bold xp-tracking-wide",
              !points ? "xp-bg-gray-200" : "xp-bg-blue-100",
            )}
          >
            {points !== null ? `${points != 0 ? "+" : ""}${points}` : "-"}
          </div>
        </div>
        <div className="xp-flex xp-grow xp-items-center">
          <div className="xp-grow xp-flex xp-justify-end xp-items-center xp-flex-wrap">
            <div className="xp-grow">{label}</div>
            {extra ? <div className="xp-shrink-0">{extra}</div> : null}
          </div>
          {type ? (
            <div className="xp-flex-0 xp-flex xp-items-center">
              <RuleLimit rule={rule} type={type} />
            </div>
          ) : null}
        </div>
        <div className="xp-shrink-0 xp--my-1 xp-flex xp-items-center">
          <RuleDropdown onDelete={onDelete} onEdit={onEdit} onViewLogs={onViewLogs} />
        </div>
      </div>
    </div>
  );
};

const LimitPerWindow = ({ max, window }: { max: number; window: LimitSpecTimeWindow }) => {
  const getStr = useStrings(["nperhoursmall", "nperdaysmall", "nperweeksmall", "npermonthsmall"]);

  const key = useMemo(() => {
    switch (window) {
      case LimitSpecTimeWindow.ONEHOUR:
      case LimitSpecTimeWindow.HOUR:
        return "nperhoursmall";
      case LimitSpecTimeWindow.DAILY:
        return "nperdaysmall";
      case LimitSpecTimeWindow.WEEKLY:
        return "nperweeksmall";
      case LimitSpecTimeWindow.MONTHLY:
        return "npermonthsmall";
    }
    return;
  }, [window]);

  if (!key) {
    return <>{max}</>;
  }

  return <>{getStr(key, max)}</>;
};

const RuleLimit = ({ rule, type }: { rule: Rule | RuleV2; type: RuleType }) => {
  const limit = useMemo(() => {
    if ("limit" in rule && rule.limit) {
      return rule.limit;
    }
    return type.defaultlimit;
  }, [rule, type]);

  const repeatlimit = useMemo(() => {
    if ("repeatlimit" in rule && rule.repeatlimit) {
      return rule.repeatlimit;
    }
    return type.defaultrepeatlimit;
  }, [rule, type]);

  if (!rule.points || !ruleTypeSupportsLimits(type)) {
    return null;
  }

  return <RuleLimitContent limit={limit} repeatlimit={repeatlimit} />;
};

const RuleLimitContent = ({ limit, repeatlimit }: { limit?: LimitSpec | null; repeatlimit?: LimitSpec | null }) => {
  const getStr = useStrings(["repetitionlimitset"]);
  if (!limit?.max && !repeatlimit?.max) {
    return null;
  }
  return (
    <div className="xp-text-xs xp-text-gray-500 xp-leading-none xp-whitespace-nowrap xp-flex xp-items-center xp-gap-1">
      {limit?.max ? (
        <span>
          Max: <LimitPerWindow max={limit.max} window={limit.timewindow} />
        </span>
      ) : null}
      {repeatlimit?.max ? (
        <Tooltip content={getStr("repetitionlimitset")}>
          <span>
            <IconRenderer icon={{ type: "fa", value: "history" }} />
          </span>
        </Tooltip>
      ) : null}
    </div>
  );
};

const noop = () => {};

export const RuleDropdown = ({
  onEdit,
  onDelete,
  onViewLogs,
}: {
  onEdit: () => void;
  onDelete: () => void;
  onViewLogs?: () => void;
}) => {
  const isAddonEnabled = useAddonActivated();
  const deleteProps = useAnchorButtonProps(onDelete);
  const viewLogsProps = useAnchorButtonProps(onViewLogs ?? noop);
  const editProps = useAnchorButtonProps(onEdit);
  return (
    <Dropdown
      buttonLabel={<Str id="options" component="core" />}
      items={[
        { id: "edit", label: <Str id="edit" component="core" />, props: editProps },
        ...(onViewLogs
          ? [
              {
                id: "logs",
                label: <Str id="viewlogs" component="block_xp" />,
                props: viewLogsProps,
                addonRequired: !isAddonEnabled,
                disabled: !isAddonEnabled,
              },
            ]
          : []),
        { id: "divider", divider: true },
        { id: "delete", label: <Str id="delete" component="core" />, props: deleteProps, danger: true },
      ]}
    />
  );
};
