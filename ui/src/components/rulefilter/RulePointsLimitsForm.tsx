import React, { useCallback, useMemo } from "react";
import { useAddonActivated, useStrings, useUniqueId } from "../../lib/hooks";
import { getInitialLimitFieldsFromRuleType, ruleTypeSupportsLimits } from "../../lib/rulelimits";
import { LimitSpecScope, LimitSpecTimeWindow, type RepeatLimitOption, type RuleConfig, type RuleType } from "../../lib/types";
import { AddonRequiredShort, IfAddonActivatedOrPromoEnabled, IfAddonPromoEnabled } from "../Addon";
import { FieldHelp, Select } from "../Input";
import { PointsToAwardInput } from "./inputs";

function filterRepeatLimitOptionsForFilter(options: RepeatLimitOption[] | undefined, filterName: string): RepeatLimitOption[] {
  return options?.filter((opt) => !opt.incompatiblewithfilters?.includes(filterName)) ?? [];
}

export const RulePointsLimitsForm = ({
  config,
  filterName,
  ruleType,
  setConfig,
}: {
  config: RuleConfig;
  filterName: string;
  ruleType: RuleType;
  setConfig: (config: RuleConfig) => void;
}) => {
  const isActivated = useAddonActivated();
  const getStr = useStrings([
    "forever",
    "intotal",
    "nolimit",
    "ntimes",
    "once",
    "overalllimit",
    "overalllimitdesc",
    "perday",
    "perhour",
    "permonth",
    "perweek",
    "pointstoaward",
    "pointstoaward_help",
    "repeatsallowed",
    "repetitionlimit",
    "repetitionlimitdesc",
    "repetitiontimeframe",
    "timesallowed",
    "timeframe",
    "unknown",
    "unlimitedrepeats",
    "usedefaultlimits",
  ]);
  const repeatOptions = useMemo(
    () => filterRepeatLimitOptionsForFilter(ruleType.repeatlimitoptions, filterName),
    [filterName, ruleType.repeatlimitoptions],
  );

  const useDefaultLimits = config.usedefaultlimits ?? true;
  const handleToggleDefaultLimits = useCallback(
    (e: React.ChangeEvent<HTMLInputElement>) => {
      setConfig({ ...config, usedefaultlimits: e.currentTarget.checked });
    },
    [config, setConfig],
  );

  const defaultLimitFields = useMemo(() => getInitialLimitFieldsFromRuleType(ruleType), [ruleType]);
  const limitMax = config.limitmax ?? defaultLimitFields.limitmax;
  const limitWindow = config.limitwindow ?? defaultLimitFields.limitwindow;
  const repeatScope = config.repeatscope ?? defaultLimitFields.repeatscope;
  const repeatWindow = config.repeatwindow ?? defaultLimitFields.repeatwindow;

  return (
    <div className="xp-space-y-4">
      <PointsToAwardInput config={config} setConfig={setConfig} />
      <If condition={ruleTypeSupportsLimits(ruleType) && (config.points ?? 1) > 0}>
        <IfAddonActivatedOrPromoEnabled>
          <div className="form-check">
            <input
              type="checkbox"
              id="xp-rule-usedefaultlimits"
              className="form-check-input"
              checked={useDefaultLimits}
              onChange={handleToggleDefaultLimits}
            />
            <label className="form-check-label" htmlFor="xp-rule-usedefaultlimits">
              {getStr("usedefaultlimits")}
            </label>
          </div>

          <If condition={!useDefaultLimits}>
            <div className="">
              <div className="xp-flex xp-items-center xp-gap-2 xp-leading-none xp-mb-1">
                <div>{getStr("overalllimit")}</div>
                <IfAddonPromoEnabled>
                  <div>
                    <AddonRequiredShort />
                  </div>
                </IfAddonPromoEnabled>
              </div>
              <div className="xp-flex xp-flex-wrap xp-gap-x-1 xp-items-end">
                <MaxTimes value={limitMax} onChange={(v) => setConfig({ ...config, limitmax: v })} />
                <If condition={Boolean(limitMax)}>
                  <TimeFrame
                    value={limitWindow}
                    onChange={(v) => setConfig({ ...config, limitwindow: v })}
                    label={getStr("timeframe")}
                    noneLabel={getStr("intotal")}
                  />
                </If>
              </div>
              <FieldHelp>{getStr("overalllimitdesc")}</FieldHelp>
            </div>

            <If condition={repeatOptions.length > 0}>
              <div className="">
                <div className="xp-flex xp-items-center xp-gap-2 xp-leading-none xp-mb-1">
                  <div>{getStr("repetitionlimit")}</div>
                  <IfAddonPromoEnabled>
                    <div>
                      <AddonRequiredShort />
                    </div>
                  </IfAddonPromoEnabled>
                </div>
                <div className="xp-flex xp-flex-wrap xp-gap-x-1 xp-items-end">
                  <div className="">
                    <label htmlFor="xp-rule-repeatscope" className="xp-sr-only xp-m-0">
                      {getStr("repeatsallowed")}
                    </label>
                    <div className="sm:xp-max-w-56">
                      <Select
                        id="xp-rule-repeatscope"
                        value={repeatScope}
                        onChange={(e) => setConfig({ ...config, repeatscope: parseInt(e.currentTarget.value, 10) })}
                      >
                        <option value={LimitSpecScope.None} disabled={repeatScope !== LimitSpecScope.None && !isActivated}>
                          {getStr("unlimitedrepeats")}
                        </option>
                        {repeatOptions.map((opt) => (
                          <option key={opt.value} value={opt.value} disabled={opt.value != repeatScope && !isActivated}>
                            {opt.oncelabel}
                          </option>
                        ))}
                      </Select>
                    </div>
                  </div>
                  <If condition={repeatScope !== LimitSpecScope.None}>
                    <TimeFrame
                      value={repeatWindow}
                      onChange={(v) => setConfig({ ...config, repeatwindow: v })}
                      label={getStr("repetitiontimeframe")}
                      noneLabel={getStr("forever")}
                    />
                  </If>
                </div>
                <FieldHelp>{getStr("repetitionlimitdesc")}</FieldHelp>
              </div>
            </If>
          </If>
        </IfAddonActivatedOrPromoEnabled>
      </If>
    </div>
  );
};

const If = ({ condition, children }: { condition: boolean; children: React.ReactNode }) => {
  if (!condition) {
    return null;
  }
  return <>{children}</>;
};

const MaxTimes = ({ value, onChange }: { value: number; onChange: (value: number) => void }) => {
  const getStr = useStrings(["nolimit", "once", "ntimes", "timesallowed"]);
  const isActivated = useAddonActivated();
  return (
    <div className="">
      <label htmlFor="xp-rule-limitmax" className="xp-sr-only xp-m-0">
        {getStr("timesallowed")}
      </label>
      <div className="sm:xp-max-w-56">
        <Select id="xp-rule-limitmax" value={value} onChange={(e) => onChange(parseInt(e.currentTarget.value, 10))}>
          <option value={0} disabled={value != 0 && !isActivated}>
            {getStr("nolimit")}
          </option>
          <option value={1} disabled={value != 1 && !isActivated}>
            {getStr("once")}
          </option>
          {[2, 3, 5, 7, 10, 12, 15, 20, 50, 100].map((n) => (
            <option key={n} value={n} disabled={value != n && !isActivated}>
              {getStr("ntimes", n)}
            </option>
          ))}
        </Select>
      </div>
    </div>
  );
};

const TimeFrame = ({
  value,
  onChange,
  label,
  noneLabel,
}: {
  value: LimitSpecTimeWindow;
  onChange: (value: LimitSpecTimeWindow) => void;
  label: string;
  noneLabel: string;
}) => {
  const id = useUniqueId();
  const getStr = useStrings(["perhour", "perday", "perweek", "permonth"]);
  const isActivated = useAddonActivated();
  const handleChange = useCallback(
    (e: React.ChangeEvent<HTMLSelectElement>) => {
      onChange(parseInt(e.currentTarget.value, 10));
    },
    [onChange],
  );
  return (
    <div>
      <label htmlFor={id} className="xp-sr-only xp-m-0">
        {label}
      </label>
      <div className="sm:xp-max-w-56">
        <Select id={id} value={value} onChange={handleChange}>
          <option value={LimitSpecTimeWindow.HOUR} disabled={value != LimitSpecTimeWindow.HOUR && !isActivated}>
            {getStr("perhour")}
          </option>
          <option value={LimitSpecTimeWindow.DAILY} disabled={value != LimitSpecTimeWindow.DAILY && !isActivated}>
            {getStr("perday")}
          </option>
          <option value={LimitSpecTimeWindow.WEEKLY} disabled={value != LimitSpecTimeWindow.WEEKLY && !isActivated}>
            {getStr("perweek")}
          </option>
          <option value={LimitSpecTimeWindow.MONTHLY} disabled={value != LimitSpecTimeWindow.MONTHLY && !isActivated}>
            {getStr("permonth")}
          </option>
          <option value={LimitSpecTimeWindow.NONE} disabled={value != LimitSpecTimeWindow.NONE && !isActivated}>
            {noneLabel}
          </option>
        </Select>
      </div>
    </div>
  );
};
