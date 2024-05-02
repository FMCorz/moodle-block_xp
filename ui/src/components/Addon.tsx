import React, { useContext, useEffect, useRef } from "react";
import { AddonContext } from "../lib/contexts";
import { useStrings } from "../lib/hooks";
import { classNames } from "../lib/utils";
import { getModule } from "../lib/moodle";

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
  const handleClick = (e: React.MouseEvent<HTMLAnchorElement>) => e.preventDefault();
  const ref = useRef<HTMLAnchorElement>(null);

  useEffect(() => {
    const handleClick = (e: MouseEvent) => {
      const $ = getModule("jquery");
      if (!$ || !ref.current || !$(ref.current).popover) {
        return;
      }

      const target = e.target as HTMLElement;
      if (target.closest(".popover")) {
        return;
      } else if (ref.current.contains(target)) {
        return;
      }

      try {
        $(ref.current).popover("hide");
      } catch (err) {}
    };
    document.body.addEventListener("click", handleClick);
    return () => document.body.removeEventListener("click", handleClick);
  });

  return (
    <a
      ref={ref}
      href="#"
      role="button"
      onClick={handleClick} /** Older popovers cause a scroll up. */
      data-bs-toggle="popover"
      data-toggle="popover"
      data-placement="top"
      data-container="body"
      data-content={getStr("unlockfeaturewithxpplus", promourl)}
      data-bs-content={getStr("unlockfeaturewithxpplus", promourl)}
      data-html="true"
      data-bs-html="true"
      className="xp-py-1 xp-px-1.5 xp-normal-case xp-text-2xs xp-inline-block xp-bg-black xp-text-white xp-rounded xp-no-underline"
    >
      {props.children ? props.children : getStr("xpplusrequired")}
    </a>
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
        "xp-rounded xp-no-underline xp-font-normal xp-align-middle xp-select-none",
      )}
    >
      XP+
    </span>
  );
};
