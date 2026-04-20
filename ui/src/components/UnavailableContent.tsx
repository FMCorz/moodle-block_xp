import React from "react";
import { AvailabilityInfo } from "../lib/types";
import Str from "./Str";

export const UnavailableContent = ({ availabilityInfo }: { availabilityInfo?: AvailabilityInfo }) => {
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
