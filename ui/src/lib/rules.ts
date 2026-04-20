import { UseQueryResult } from "@tanstack/react-query";
import { useMemo } from "react";
import { cmConfigSettings } from "../components/rulefilter/cm";
import { cmNameConfigSettings } from "../components/rulefilter/cmname";
import { cmtagConfigSettings } from "../components/rulefilter/cmtag";
import { sectionConfigSettings } from "../components/rulefilter/section";
import { getUnavailableConfigSettings } from "../components/rulefilter/unavailable";
import { getUnknownConfigSettings } from "../components/rulefilter/unknown";
import { useRuleCreationLimit } from "./hooks";
import { RuleTypeGoal, type RuleFilter, type RuleFilterConfigSettings, type RuleType, type RuleV2 } from "./types";
import { groupBy, mapFrom, uniq } from "./utils";

const noSettings: RuleFilterConfigSettings = { hasContent: false };
const specialTypes = ["consume_content", "produce_content"];

export function getFilterContentSettings(filter: RuleFilter): RuleFilterConfigSettings {
  if (!(filter.availabilityinfo?.isavailable ?? true)) {
    return getUnavailableConfigSettings(filter);
  }
  if (filter.name === "cmname") {
    return cmNameConfigSettings;
  } else if (filter.name === "cmtag") {
    return cmtagConfigSettings;
  } else if (filter.name === "cm") {
    return cmConfigSettings;
  } else if (filter.name === "section") {
    return sectionConfigSettings;
  } else if (filter.name.startsWith("any") || filter.name === "thiscourse") {
    return noSettings;
  }
  return getUnknownConfigSettings(filter);
}

export function getTypeThemeColor(type: RuleType): undefined | [string, string] {
  switch (type.goal) {
    case RuleTypeGoal.Assess:
      return ["#fff", "#f43f5e"];
    case RuleTypeGoal.Comms:
      return ["#fff", "#a855f7"];
    case RuleTypeGoal.Contrib:
      return ["#fff", "#22c55e"];
    case RuleTypeGoal.Read:
      return ["#fff", "#3b82f6"];
  }
  return undefined;
}

export const useMakeRulesSetupContext = (props: { ruletypes: RuleType[]; rulefilters: RuleFilter[] }) => {
  const typesByName = useMemo(() => {
    return mapFrom(props.ruletypes, "name");
  }, [props.ruletypes]);

  const filtersByName = useMemo(() => {
    return mapFrom(props.rulefilters, "name");
  }, [props.rulefilters]);

  return {
    types: typesByName,
    filters: filtersByName,
  };
};

export const useRulesInfo = (query: UseQueryResult<RuleV2[]>, deleted: number[]) => {
  const limit = useRuleCreationLimit();

  const rules = useMemo(() => {
    const nonDeleted = query.data?.filter((rule) => !deleted.includes(rule.id)) ?? [];
    const countByType = new Map<string, number>();
    const filtered =
      limit > 0
        ? nonDeleted.reduce<RuleV2[]>((carry, rule) => {
            const effectiveSize = Array.from(countByType.keys()).filter((type) => !specialTypes.includes(type)).length;
            const currentCount = countByType.get(rule.typename) ?? 0;
            const isTypeAllowed =
              specialTypes.includes(rule.typename) ||
              countByType.has(rule.typename) ||
              (!countByType.has(rule.typename) && effectiveSize < limit);
            const isFilterAllowed = currentCount < limit;
            if (isTypeAllowed && isFilterAllowed) {
              countByType.set(rule.typename, (countByType.get(rule.typename) ?? 0) + 1);
              carry.push(rule);
            }
            return carry;
          }, [])
        : nonDeleted;
    return filtered;
  }, [query.data, deleted, limit]);

  const byType = useMemo(() => {
    return groupBy(rules, "typename");
  }, [rules]);

  const filtersUsageByType = useMemo(() => {
    return new Map(
      Array.from(byType).map(([type, rules]) => {
        return [type, uniq(rules.map((rule) => rule.filtername))];
      }),
    );
  }, [byType]);

  return {
    byType,
    rules,
    filtersUsageByType,
  };
};
