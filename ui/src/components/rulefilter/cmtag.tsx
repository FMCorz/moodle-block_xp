import React from "react";
import { RuleFilterConfigSettings, RuleFilterConfigSettingsContentProps } from "../../lib/types";
import Input, { FieldHelp } from "../Input";
import Str from "../Str";
import { RulePointsLimitsForm } from "./RulePointsLimitsForm";

export const cmtagConfigSettings: RuleFilterConfigSettings = {
  hasContent: true,
  getContent: (props: RuleFilterConfigSettingsContentProps) => <CmtagContent {...props} />,
  isConfigValid: (config) =>
    typeof config.filterchar1 === "string" &&
    config.filterchar1.trim() !== "" &&
    typeof config?.points === "number" &&
    !isNaN(config.points),
  contentIncludesPoints: true,
  contentRequiresSubmit: true,
};

const CmtagContent = ({ config, setConfig, type }: RuleFilterConfigSettingsContentProps) => {
  return (
    <>
      <div className="xp-mb-4">
        <label htmlFor="xp-rule-cmtag-name" className="xp-m-0">
          <Str id="rulefiltercmtagfield" />
        </label>
        <Input
          id="xp-rule-cmtag-name"
          value={config.filterchar1 || ""}
          onChange={(e) => setConfig({ filterchar1: e.currentTarget.value })}
          maxLength={255}
        />
        <FieldHelp>
          <Str id="rulefiltercmtaghelp" />
        </FieldHelp>
      </div>
      <RulePointsLimitsForm config={config} setConfig={setConfig} ruleType={type} filterName="cmtag" />
    </>
  );
};
