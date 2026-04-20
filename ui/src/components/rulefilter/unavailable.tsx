import React from "react";
import { RuleFilter } from "../../lib/types";
import { UnavailableContent } from "../UnavailableContent";

export const getUnavailableConfigSettings = (filter: RuleFilter) => {
  return {
    hasContent: true,
    getContent: () => <UnavailableContent availabilityInfo={filter.availabilityinfo} />,
    contentIncludesPoints: false,
    contentRequiresSubmit: true,
    isConfigValid: () => false,
  };
};
