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

export interface PointCalculationMethod {
  method: "flat" | "linear" | "relative";
  base: number;
  coef: number; // Float. e.g. 1.2 = 20% increase.
  incr: number;
}
