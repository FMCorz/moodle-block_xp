import React, { AnchorHTMLAttributes, ButtonHTMLAttributes } from "react";
import { useAnchorButtonProps, useStrings } from "../lib/hooks";
import Pix from "./Pix";
import Spinner from "./Spinner";
import Str from "./Str";
import { classNames } from "../lib/utils";

export const CircleButton = ({ className, ...props }: ButtonHTMLAttributes<HTMLButtonElement>) => {
  return (
    <button
      className={classNames(
        "xp-bg-transparent xp-border-0 xp-p-2 xp-flex xp-items-center xp-rounded-full xp-duration-150 xp-transition-colors",
        "hover:xp-bg-gray-200",
        className
      )}
      type="button"
      {...props}
    />
  );
};

export const Button = ({
  onClick,
  disabled,
  children,
  primary,
  outline,
  className,
  type = "button",
}: {
  disabled?: boolean;
  onClick?: () => void;
  label?: string;
  primary?: boolean;
  outline?: boolean;
  className?: React.ButtonHTMLAttributes<HTMLButtonElement>["className"];
  type?: React.ButtonHTMLAttributes<HTMLButtonElement>["type"];
  children?: React.ButtonHTMLAttributes<HTMLButtonElement>["children"];
}) => {
  const classes = classNames(
    "btn",
    primary ? `btn-${outline ? "outline-" : ""}primary` : `btn-default btn-${outline ? "outline-" : ""}secondary`,
    className
  );
  return (
    <button className={classes} onClick={onClick} disabled={disabled} type={type}>
      {children}
    </button>
  );
};

export const ExpandCollapseButton = ({
  expanded,
  onToggle,
  ariaControlsId,
}: {
  expanded: boolean;
  onToggle: () => void;
  ariaControlsId: string;
}) => {
  return (
    <AnchorButton
      aria-expanded={expanded}
      aria-controls={ariaControlsId}
      onClick={onToggle}
      className="xp-p-2 xp-inline-block sm:xp-mr-1"
    >
      <span className="xp-sr-only">{expanded ? <Str id="collapse" component="core" /> : <Str id="expand" component="core" />}</span>
      <svg
        xmlns="http://www.w3.org/2000/svg"
        fill="none"
        viewBox="0 0 24 24"
        strokeWidth={1.5}
        stroke="currentColor"
        className={classNames("xp-w-6 xp-h-6 xp-transition-transform xp-duration-300", expanded ? "xp-rotate-90" : null)}
      >
        <path strokeLinecap="round" strokeLinejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
      </svg>
    </AnchorButton>
  );
};

export const SaveButton = ({
  onClick,
  disabled,
  label,
  mutation = {},
  statePosition = "after",
}: {
  mutation?: any;
  disabled?: boolean;
  onClick?: () => void;
  label?: string;
  statePosition?: "before" | "after";
}) => {
  const getStr = useStrings(["changessaved", "error"], "core");
  const { isLoading, isSuccess, isError } = mutation;
  const isStateBefore = statePosition === "before";

  const state = (
    <div className={`xp-w-8 xp-flex ${isStateBefore ? "xp-mr-4 xp-justify-end" : "xp-ml-4"}`} aria-live="assertive">
      {isLoading ? <Spinner /> : null}
      {isSuccess ? <Pix id="i/valid" component="core" alt={getStr("changessaved")} /> : null}
      {isError ? <Pix id="i/invalid" component="core" alt={getStr("error")} /> : null}
    </div>
  );

  return (
    <div className="xp-flex xp-items-center">
      {isStateBefore ? state : null}
      <div className="">
        <Button primary onClick={onClick} disabled={disabled || isLoading}>
          {label || <Str id="savechanges" component="core" />}
        </Button>
      </div>
      {!isStateBefore ? state : null}
    </div>
  );
};

export const AnchorButton = ({
  children,
  onClick,
  className,
  ...props
}: { onClick: () => void } & AnchorHTMLAttributes<HTMLAnchorElement>) => {
  const anchorButtonProps = useAnchorButtonProps(onClick);
  return (
    <a className={classNames("xp-text-inherit xp-no-underline", className)} {...props} {...anchorButtonProps}>
      {children}
    </a>
  );
};
