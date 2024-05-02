import React from "react";
import { RuleConfig } from "../../lib/types";
import { NumberInputWithButtons } from "../NumberInput";
import Str from "../Str";
import { FieldHelp } from "../Input";

export const PointsToAwardInput = ({ setConfig, config }: { config: RuleConfig; setConfig: (data: RuleConfig) => void }) => {
  return (
    <div>
      <label htmlFor="xp-rule-pointstoaward" className="xp-m-0">
        <Str id="pointstoaward" />
      </label>
      <div>
        <NumberInputWithButtons
          value={config.points ?? 10}
          onChange={(points) => setConfig({ ...config, points })}
          min={0}
          max={9999999}
          inputProps={{ id: "xp-rule-pointstoaward", className: "xp-w-24", selectOnFocus: true }}
        />
      </div>
      <FieldHelp>
        <Str id="pointstoaward_help" />
      </FieldHelp>
    </div>
  );
};
