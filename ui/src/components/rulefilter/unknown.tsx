import React from "react";
import { RuleFilter, RuleFilterConfigSettingsContentProps } from "../../lib/types";
import Str from "../Str";

export const getUnknownConfigSettings = (filter: RuleFilter) => {
  return {
    hasContent: true,
    getContent: (props: RuleFilterConfigSettingsContentProps) => <UnknownContent filter={filter} />,
    contentIncludesPoints: false,
    contentRequiresSubmit: true,
    isConfigValid: () => false,
  };
};

const UnknownContent = ({ filter }: { filter: RuleFilter }) => {
  return (
    <>
      <p>
        <Str id="unknowntypea" a={filter.name} />
      </p>
    </>
  );
};
