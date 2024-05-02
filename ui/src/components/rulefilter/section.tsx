import React, { useContext } from "react";
import { RulesSetupContext } from "../../lib/contexts";
import { ContextLevel, RuleFilterConfigSettings, RuleFilterConfigSettingsContentProps } from "../../lib/types";
import { SectionResourceList } from "../SectionResourceList";

const Content = (props: RuleFilterConfigSettingsContentProps) => {
  const { context } = useContext(RulesSetupContext);
  return (
    <SectionResourceList
      courseId={context.contextlevel === ContextLevel.Course ? context.instanceid : 0}
      options={{}}
      onSelect={(sectionNum: number) => {
        props.setConfig({ filterint1: sectionNum });
        props.onContinue();
      }}
    />
  );
};

export const sectionConfigSettings: RuleFilterConfigSettings = {
  hasContent: true,
  getContent: (props: RuleFilterConfigSettingsContentProps) => <Content {...props} />,
  isConfigValid: (config) => typeof config.filterint1 === "number",
  contentIncludesPoints: false,
  contentRequiresSubmit: false,
};
