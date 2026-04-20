export type AppCommonProps = {
  world?: { contextid: number; contextlevel: ContextLevel; contextinstanceid: number; courseid: number };
  addon: {
    activated: boolean;
    enablepromo: boolean;
    promourl: string;
  };
};

export type AvailabilityInfo = {
  isavailable: boolean;
  reasons: { code: string; description: string }[];
};

export enum ContextLevel {
  System = 10,
  User = 30,
  CourseCategory = 40,
  Course = 50,
  Module = 70,
}

export type Icon = IconFa;

export interface IconFa {
  type: "fa";
  value: string;
}

export interface Level {
  level: number;
  xprequired: number;
  description: string | null;
  name: string | null;
  badgeurl: string | null;
  badgeawardid?: number | null;
  popupmessage?: string | null;
}

export interface LevelsInfo {
  count: number;
  levels: Level[];
  algo: Omit<PointCalculationMethod, "method" | "incr"> &
    // Method and incr are not guaranteed to be present.
    Partial<Pick<PointCalculationMethod, "method" | "incr">> & {
      /** @deprecated No longer used. */
      enabled?: boolean;
    };
}

export enum LimitSpecTimeWindow {
  NONE = 0,
  ONEHOUR = 1,
  DAILY = 2,
  WEEKLY = 3,
  MONTHLY = 4,
  HOUR = 5,
}

export enum LimitSpecScope {
  None = 0,
  Env = 2,
  Parent = 4,
  Object = 8,
}

export type LimitSpec = {
  max: number;
  timewindow: LimitSpecTimeWindow;
  scope: number;
};

export type MoodleContext = {
  id: number;
  contextlevel: ContextLevel;
  instanceid: number;
};

export interface PointCalculationMethod {
  method: "flat" | "linear" | "relative";
  base: number;
  coef: number; // Float. e.g. 1.2 = 20% increase.
  incr: number;
}

export type RepeatLimitOption = {
  value: number;
  oncelabel: string;
  incompatiblewithfilters: string[];
};

interface ResourceBase<TName extends string | number = string | number> {
  type?: string;
  name: TName; // A name uniquely identifying this resource.
  label: string;
  description?: string;
  icon?: Icon;
  themecolor?: [string, string];
  availabilityinfo?: AvailabilityInfo;
}

export interface ResourceItem<TName extends string | number = string | number> extends ResourceBase<TName> {}

export interface ResourceHeading<TName extends string | number = string | number> extends ResourceBase<TName> {
  type: "header";
}

export type Resource<TName extends string | number = string | number> = ResourceBase<TName> | ResourceHeading<TName>;

export type Rule = {
  id: number;
  points: number;
  method: string;
  filter: string;
  label: string;
};

export type RuleV2 = {
  id: number;
  points: number;
  typename: string;
  filtername: string;
  label: string;
  limit?: LimitSpec | null;
  repeatlimit?: LimitSpec | null;
};

export type RuleConfig = {
  type?: string;
  typechar1?: string;
  filter?: string;
  filterint1?: number;
  filterchar1?: string;
  filtercmid?: number;
  filtercourseid?: number;
  limitmax?: number;
  limitwindow?: number;
  points?: number;
  repeatscope?: number;
  repeatwindow?: number;
  usedefaultlimits?: boolean;
};

export type RuleType = ResourceItem<string> & {
  defaultlimit?: LimitSpec | null;
  defaultrepeatlimit?: LimitSpec | null;
  filters: string[];
  goal?: RuleTypeGoal;
  profile?: RuleTypeProfile;
  repeatlimitoptions?: RepeatLimitOption[];

  /** @deprecated  */
  scope?: null | undefined;
  /** @deprecated */
  repeatwindow?: null | string;
};

export enum RuleTypeGoal {
  Comms = "comms",
  Contrib = "contrib",
  Read = "read",
  Assess = "assess",
}

export enum RuleTypeProfileSubject {
  Cm = "cm",
  Section = "section",
  Course = "course",
}

export type RuleTypeProfile = {
  subject: RuleTypeProfileSubject | null;
  cmtype: string | null;
  requirescompletionenabled: boolean;
};

/** @deprecated Use RuleTypeProfile instead. */
export type RuleTypeScope = {
  cmtype?: string;
  hascompletionenabled?: boolean;
};

export type RuleFilter = ResourceItem<string> & { weight: number; ismultipleallowed: boolean };

export type RuleFilterConfigSettingsContentProps = {
  type: RuleType;
  config: RuleConfig;
  setConfig: (config: RuleConfig) => void;
  onContinue: () => void;
};

export type RuleFilterConfigSettings =
  | {
      // We do not support multiple steps for now.
      hasContent: true;
      getContent: (props: RuleFilterConfigSettingsContentProps) => JSX.Element;
      contentIncludesPoints: boolean;
      contentRequiresSubmit: boolean;
      isConfigValid: (config: RuleConfig) => boolean;
    }
  | {
      hasContent: false;
    };

export type RuleTypeConfigSettingsContentProps = {
  type: RuleType;
};

export type RuleTypeConfigSettings =
  | {
      hasContent: true;
      getContent: (props: RuleTypeConfigSettingsContentProps) => JSX.Element;
    }
  | {
      hasContent: false;
    };

/**
 * Moodle modules.
 *
 * Those modules must have a signature that covers all our supported versions of Moodle.
 */

type JQuery = {
  0: HTMLElement;
  on(event: string, listener: (...args: any[]) => void): void;
  off(event: string, listener: (...args: any[]) => void): void;
};

export type CoreModal = {
  getActionSelector(action: string): string;
  getBody(): JQuery;
  getFooter(): JQuery;
  getRoot(): JQuery;
  hide(): void;
  setTitle: (title: string | Promise<string>) => void;
  show(): Promise<void>;
};

export type CoreModalFormInstance = {
  events: { [index: string]: string };
  modal?: CoreModal;
  show(): Promise<void>;
  addEventListener(event: string, listener: (...args: any[]) => void): void;
  removeEventListener(event: string, listener: (...args: any[]) => void): void;
};

export type CoreModalForm = new (config: any) => CoreModalFormInstance;

export type CorePending = new (pendingKey?: string) => Promise<void> & {
  resolve: (value?: unknown) => void;
  reject: (reason?: unknown) => void;
};
