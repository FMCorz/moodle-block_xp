import React, { useContext } from "react";
import { ContextLevel, RuleFilterConfigSettings, RuleFilterConfigSettingsContentProps } from "../../lib/types";
import { CmResourceList } from "../CmResourceList";
import { RulesSetupContext, WorldContext } from "../../lib/contexts";

const Content = (props: RuleFilterConfigSettingsContentProps) => {
  const { context } = useContext(RulesSetupContext);
  return (
    <CmResourceList
      courseId={context.contextlevel === ContextLevel.Course ? context.instanceid : 0}
      options={{
        completionenabled: props.type.profile?.requirescompletionenabled,
        type: props.type.profile?.cmtype ?? undefined,
      }}
      onSelect={(cmid: number) => {
        props.setConfig({ filtercmid: cmid });
        props.onContinue();
      }}
    />
  );
};

export const cmConfigSettings: RuleFilterConfigSettings = {
  hasContent: true,
  getContent: (props: RuleFilterConfigSettingsContentProps) => <Content {...props} />,
  isConfigValid: (config) => Boolean(config.filtercmid),
  contentIncludesPoints: false,
  contentRequiresSubmit: false,
};
