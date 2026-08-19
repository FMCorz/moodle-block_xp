import React, { useContext } from "react";
import { AddonContext } from "../lib/contexts";
import { useStrings } from "../lib/hooks";
import { classNames } from "../lib/utils";
import { AnchorPopover } from "./Popover";

export const IfAddonActivatedOrPromoEnabled = ({ children }: { children: React.ReactNode }) => {
  const { activated, enablepromo } = useContext(AddonContext);
  if (!activated && !enablepromo) {
    return null;
  }
  return <>{children}</>;
};

export const IfAddonPromoEnabled = ({ children }: { children: React.ReactNode }) => {
  const { activated, enablepromo } = useContext(AddonContext);
  if (activated || !enablepromo) {
    return null;
  }
  return <>{children}</>;
};

export const AddonRequired = (props: { children?: React.ReactNode }) => {
  const { promourl } = useContext(AddonContext);
  const getStr = useStrings(["xpplusrequired", "unlockfeaturewithxpplus"]);

  return (
    <AnchorPopover
      content={getStr("unlockfeaturewithxpplus", promourl)}
      className={classNames(
        "xp-py-1 xp-px-1.5 xp-normal-case xp-text-2xs xp-inline-block xp-bg-black xp-text-white",
        "xp-rounded xp-no-underline"
      )}
    >
      {props.children ? props.children : getStr("xpplusrequired")}
    </AnchorPopover>
  );
};

export const AddonRequiredShort = () => {
  return <AddonRequired>XP+</AddonRequired>;
};

export const AddonTag = () => {
  return (
    <span
      className={classNames(
        "xp-py-0.5 xp-px-1 xp-normal-case xp-text-2xs xp-inline-block xp-bg-black xp-text-white",
        "xp-rounded xp-no-underline xp-font-normal xp-align-middle xp-select-none"
      )}
    >
      XP+
    </span>
  );
};
