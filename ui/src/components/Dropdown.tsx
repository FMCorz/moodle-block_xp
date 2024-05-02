import React, { useContext, useMemo } from "react";
import { AddonContext } from "../lib/contexts";
import { classNames } from "../lib/utils";
import { AddonTag, IfAddonPromoEnabled } from "./Addon";

type DropdownProps = {
  buttonLabel: React.ReactNode;
  items: (
    | {
        id: string;
        divider: true;
      }
    | {
        id: string;
        addonRequired?: boolean;
        disabled?: boolean;
        label: React.ReactNode;
        danger?: boolean;
        props: Omit<React.DetailedHTMLProps<React.AnchorHTMLAttributes<HTMLAnchorElement>, HTMLAnchorElement>, "className">;
      }
  )[];
};

export const Dropdown = ({ buttonLabel, items }: DropdownProps) => {
  const { activated, enablepromo } = useContext(AddonContext);
  const filteredItems = useMemo(() => {
    return items.filter((item) => {
      if ("addonRequired" in item && item.addonRequired && !activated && !enablepromo) {
        return false;
      }
      return true;
    });
  }, [items, activated, enablepromo]);

  if (filteredItems.length === 0) {
    return null;
  }

  return (
    <div className="dropdown">
      <button
        type="button"
        className="btn btn-link btn-icon icon-size-3 rounded-circle xp-no-underline hover:xp-no-underline"
        data-bs-toggle="dropdown"
        data-toggle="dropdown"
        aria-expanded="false"
      >
        <i className="fa fa-ellipsis-v text-dark py-2" aria-hidden="true"></i>
        <span className="xp-sr-only">{buttonLabel}</span>
      </button>
      <div className="dropdown-menu dropdown-menu-right dropdown-menu-end">
        {filteredItems.map((item) => {
          if ("divider" in item) {
            return <div key={item.id} className="dropdown-divider" />;
          }
          return (
            <a
              key={item.id}
              {...item.props}
              aria-disabled={item.disabled ? true : undefined}
              tabIndex={item.disabled ? -1 : undefined}
              className={classNames("dropdown-item", item.disabled && "disabled xp-not-italic", item.danger ? "text-danger" : null)}
            >
              <div className="xp-flex xp-w-full xp-gap-2">
                <div className="xp-grow">{item.label}</div>
                {item.addonRequired ? (
                  <IfAddonPromoEnabled>
                    <div className="xp-flex-0 xp-self-center">
                      <AddonTag />
                    </div>
                  </IfAddonPromoEnabled>
                ) : null}
              </div>
            </a>
          );
        })}
      </div>
    </div>
  );
};
