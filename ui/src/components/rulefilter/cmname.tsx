import React from "react";
import { useStrings } from "../../lib/hooks";
import { RuleFilterConfigSettings, RuleFilterConfigSettingsContentProps } from "../../lib/types";
import Input, { Select } from "../Input";
import Str from "../Str";
import { RulePointsLimitsForm } from "./RulePointsLimitsForm";

export const cmNameConfigSettings: RuleFilterConfigSettings = {
  hasContent: true,
  getContent: (props: RuleFilterConfigSettingsContentProps) => <CmNameContent {...props} />,
  isConfigValid: (config) =>
    [0, 1].includes(config?.filterint1 ?? -1) &&
    typeof config.filterchar1 === "string" &&
    config.filterchar1.trim() !== "" &&
    typeof config?.points === "number" &&
    !isNaN(config.points),
  contentIncludesPoints: true,
  contentRequiresSubmit: true,
};

const CmNameContent = ({ config, setConfig, type }: RuleFilterConfigSettingsContentProps) => {
  const defaultValue = 1;
  const getStr = useStrings(["rule:eq", "rule:contains", "rulefiltercmname"]);
  return (
    <>
      <div className="xp-mb-4">
        <label htmlFor="xp-rule-cmname-name" className="xp-m-0">
          <Str id="activityname" />
        </label>
        <div className="xp-flex xp-gap-2">
          <label htmlFor="xp-rule-cmname-method" className="xp-sr-only">
            <Str id="comparisonmethod" />
          </label>
          <Select
            id="xp-rule-cmname-method"
            value={config.filterint1}
            onChange={(e) => setConfig({ filterint1: parseInt(e.currentTarget.value, 10) || 0 })}
            defaultValue={defaultValue.toString()}
            className="xp-w-auto"
          >
            <option value="1">{getStr("rule:contains")}</option>
            <option value="0">{getStr("rule:eq")}</option>
          </Select>
          <Input
            id="xp-rule-cmname-name"
            value={config.filterchar1 || ""}
            onChange={(e) => setConfig({ filterchar1: e.currentTarget.value, filterint1: config.filterint1 ?? defaultValue })}
            maxLength={255}
          />
        </div>
        <p className="xp-text-gray-500 xp-m-0 xp-mt-1">
          <Str id="activityname_help" />
        </p>
      </div>
      <RulePointsLimitsForm config={config} setConfig={setConfig} ruleType={type} filterName="cmname" />
    </>
  );
};
