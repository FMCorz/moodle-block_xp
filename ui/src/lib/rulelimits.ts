import { LimitSpecScope, LimitSpecTimeWindow, type RuleType } from "./types";

/** Mirrors {@see \block_xp\form\rule::get_default_data()} limit fields when the rule type has defaults. */
export function getInitialLimitFieldsFromRuleType(ruleType: RuleType): {
  limitmax: number;
  limitwindow: LimitSpecTimeWindow;
  repeatscope: LimitSpecScope;
  repeatwindow: LimitSpecTimeWindow;
} {
  const dl = ruleType.defaultlimit;
  const dr = ruleType.defaultrepeatlimit;
  if (!dl || !dr) {
    return {
      limitmax: 0,
      limitwindow: LimitSpecTimeWindow.NONE,
      repeatscope: LimitSpecScope.None,
      repeatwindow: LimitSpecTimeWindow.NONE,
    };
  }
  const isUnlimited = dr.max === 0;
  return {
    limitmax: dl.max,
    limitwindow: dl.timewindow,
    repeatscope: isUnlimited ? LimitSpecScope.None : dr.scope,
    repeatwindow: isUnlimited ? dl.timewindow : dr.timewindow,
  };
}

export function ruleTypeSupportsLimits(ruleType: RuleType): boolean {
  return (
    ruleType.defaultlimit !== null &&
    ruleType.defaultlimit !== undefined &&
    ruleType.defaultrepeatlimit !== null &&
    ruleType.defaultrepeatlimit !== undefined
  );
}
