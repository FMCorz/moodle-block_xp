import { createContext } from "react";
import { getUrl } from "./moodle";
import { AppCommonProps, ContextLevel, MoodleContext, RuleFilter, RuleType } from "./types";

const defaultMoodleContext: MoodleContext = {
  id: 0,
  contextlevel: ContextLevel.System,
  instanceid: 0,
};

export const AddonContext = createContext({
  activated: false,
  enablepromo: true,
  promourl: "https://www.levelup.plus/xp/", // Local promo page where possible.
});

export const RulesSetupContext = createContext<{
  addRule: (type?: string) => void;
  editRule: (ruleId: number) => void;
  removeRule: (ruleId: number) => void;
  viewRuleLogs?: (ruleId: number) => void;
  context: MoodleContext; // This is the current context, so either world context or child context.
  types: Map<string, RuleType>;
  filters: Map<string, RuleFilter>;
  filtersUsageByType: Map<string, string[]>;
}>({
  addRule: () => {},
  editRule: (id: number) => {},
  removeRule: (id: number) => {},
  context: defaultMoodleContext,
  types: new Map(),
  filters: new Map(),
  filtersUsageByType: new Map(),
});

type RouteParams = Record<string, string | number | undefined | null>;

export const WorldContext = createContext<{
  context: MoodleContext;
  courseid: number;
  navigateTo: (route: string, params?: RouteParams) => void;
}>({
  context: defaultMoodleContext,
  courseid: 0,
  navigateTo: () => {},
});

export const makeAddonContextValueFromAppProps = (props?: Pick<AppCommonProps, "addon">) => {
  return {
    activated: false,
    enablepromo: true,
    promourl: "https://www.levelup.plus/xp/",
    ...(props?.addon ?? {}),
  };
};

export const makeWorldContextValueFromAppProps = ({ world }: Pick<AppCommonProps, "world">) => {
  const courseId = world?.courseid ?? (world?.contextlevel === ContextLevel.Course ? (world?.contextinstanceid ?? 0) : 0);

  const resolveRoute = (routeName: string, params?: RouteParams) => {
    // Shallow implementation, does not support all kinds of routes.
    return getUrl(
      `/blocks/xp/index.php/${routeName}/${courseId}`,
      new URLSearchParams(Object.entries(params ?? {}).map(([key, value]) => [key, value?.toString() ?? ""])),
    );
  };

  const navigateTo = (route: string, params?: RouteParams) => {
    window.location.href = resolveRoute(route, params);
  };

  return {
    context: {
      id: world?.contextid ?? 0,
      contextlevel: world?.contextlevel ?? ContextLevel.System,
      instanceid: courseId,
    },
    courseid: courseId,
    navigateTo,
  };
};
