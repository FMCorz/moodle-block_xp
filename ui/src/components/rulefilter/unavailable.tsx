import React from "react";
import { AvailabilityInfo, RuleFilter, RuleFilterConfigSettingsContentProps } from "../../lib/types";
import Str from "../Str";

export const getUnavailableConfigSettings = (filter: RuleFilter) => {
  return {
    hasContent: true,
    getContent: (props: RuleFilterConfigSettingsContentProps) => <UnavailableContent availabilityInfo={filter.availabilityinfo} />,
    contentIncludesPoints: false,
    contentRequiresSubmit: true,
    isConfigValid: () => false,
  };
};

const UnavailableContent = ({ availabilityInfo }: { availabilityInfo?: AvailabilityInfo }) => {
  return (
    <>
      <p>
        <Str id="unavailablebecause" />
      </p>
      <ul>
        {availabilityInfo?.reasons.map((ai, idx) => {
          return <li key={`${ai.code}-${idx}`}>{ai.description}</li>;
        })}
      </ul>
    </>
  );
};
