import { QueryClient, UseMutationOptions, useMutation, useQuery, useQueryClient } from "@tanstack/react-query";
import { useCallback, useMemo } from "react";
import { useAddonActivated } from "./hooks";
import { ajaxRequest, getModule } from "./moodle";
import { getInitialLimitFieldsFromRuleType, ruleTypeSupportsLimits } from "./rulelimits";
import { LimitSpec, RuleConfig, RuleType, RuleV2 } from "./types";

export const queryClient = new QueryClient({
  defaultOptions: {
    queries: {
      staleTime: 1000 * 60,
      onError: (err) => getModule("core/notification").exception(err),
    },
    mutations: {
      onError: (err) => getModule("core/notification").exception(err),
    },
  },
});

export const useAddRuleMutation = (
  contextid: number,
  childcontextid: number | null,
  { types }: { types: Map<string, RuleType> },
  { onSuccess }: { onSuccess: UseMutationOptions<any, any, RuleConfig, any>["onSuccess"] },
) => {
  const addonActivated = useAddonActivated();

  return useMutation(
    async ({ type, filter, ...config }: RuleConfig) => {
      const ruleid = await ajaxRequest<number>("block_xp_create_rule", {
        contextid,
        childcontextid: childcontextid ?? 0,
        points: config.points ?? 0,
        type: {
          name: type,
          char1: config.typechar1 ?? null,
        },
        filter: {
          name: filter,
          courseid: config.filtercourseid ?? null,
          cmid: config.filtercmid ?? null,
          int1: config.filterint1 ?? null,
          char1: config.filterchar1 ?? null,
        },
      });

      const ruleType = types.get(type!);
      if (addonActivated && !config.usedefaultlimits && ruleType && ruleTypeSupportsLimits(ruleType)) {
        const initialLimits = getInitialLimitFieldsFromRuleType(ruleType);
        await ajaxRequest("local_xp_set_rule_limits", {
          ruleid,
          limits: {
            limitmax: config.limitmax ?? initialLimits.limitmax,
            limitwindow: config.limitwindow ?? initialLimits.limitwindow,
            repeatscope: config.repeatscope ?? initialLimits.repeatscope,
            repeatwindow: config.repeatwindow ?? initialLimits.repeatwindow,
          },
        });
      }

      return ruleid;
    },
    {
      onSuccess,
    },
  );
};

export const useDeleteRuleMutation = () => {
  return useMutation(async ({ id }: { id: number }) => {
    return ajaxRequest("block_xp_delete_rule", { id });
  });
};

export const useRules = (kind: "action" | "completion", contextid: number, childcontextid: number | null) => {
  const queryClient = useQueryClient();
  const queryKey = useMemo(() => [`${kind}-rules`, contextid, childcontextid], [kind, contextid, childcontextid]);
  const data = useQuery<RuleV2[]>({
    queryKey,
    queryFn: async () => {
      return await ajaxRequest<
        {
          id: number;
          points: number;
          typename: string;
          filtername: string;
          label: string;
        }[]
      >("block_xp_get_rules", {
        kind,
        contextid,
        childcontextid,
      });
    },
  });

  const invalidateQuery = useCallback(() => {
    queryClient.invalidateQueries({ queryKey });
  }, [queryClient, queryKey]);

  return {
    ...data,
    invalidateQuery,
  };
};

type RuleTypeLimits = Map<RuleType["name"], LimitSpec>;

export const invalidateRuleTypeLimitsQuery = (contextid: number, childcontextid: number | null) => {
  queryClient.invalidateQueries({ queryKey: ["ruletype-limits", contextid, childcontextid] });
};
