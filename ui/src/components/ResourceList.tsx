import React, { ReactNode } from "react";
import { useRoleButtonListeners, useStrings, useUniqueId } from "../lib/hooks";
import { AvailabilityInfo, Icon, Resource } from "../lib/types";
import { classNames } from "../lib/utils";
import { IconRenderer } from "./Icons";
import Str from "./Str";

type ResourceListProps<T extends Resource> = { resources: T[]; onSelect?: (r: T) => void };

const UnavailabilityPills = ({ availabilityInfo }: { availabilityInfo: AvailabilityInfo }) => {
  const getStr = useStrings(["unavailable", "alreadyused", "xpplusrequired"]);
  return (
    <>
      {availabilityInfo.reasons.map((ai, idx) => {
        let desc = getStr("unavailable");
        let badgeType = "badge-warning";
        if (ai.code === "xpplusrequired") {
          badgeType = "badge-dark";
          desc = getStr("xpplusrequired");
        } else if (ai.code === "xppremiumrequired") {
          badgeType = "badge-dark";
          desc = "XP+ Premium";
        } else if (ai.code === "alreadyused") {
          badgeType = "badge-primary";
          desc = getStr("alreadyused");
        }
        return (
          <span key={`${ai.code}-${idx}`} className={classNames("badge badge-pill", badgeType)}>
            {desc}
          </span>
        );
      })}
    </>
  );
};

const ListEntry = <T extends Resource>({ resource, onSelect }: { resource: T; onSelect: () => void }) => {
  if (resource.type === "header") {
    return <ListEntryHeader label={resource.label} />;
  }
  return (
    <ListEntryItem
      label={resource.label}
      description={resource.description}
      availabilityInfo={resource?.availabilityinfo}
      icon={resource.icon}
      themeColor={resource.themecolor}
      onSelect={onSelect}
    />
  );
};

const ListEntryItem = ({
  label,
  description,
  availabilityInfo,
  onSelect,
  icon,
  themeColor,
}: {
  label: string;
  description?: string;
  availabilityInfo?: AvailabilityInfo;
  icon?: Icon;
  themeColor?: [string, string];
  onSelect: () => void;
}) => {
  const [themeFgColor, themeBgColor] = themeColor || [];
  const headingId = useUniqueId();
  const buttonListeners = useRoleButtonListeners(onSelect);
  const isAvailable = availabilityInfo?.isavailable ?? true;
  const disabledOpacityClass = `${!isAvailable ? "xp-opacity-60 group-focus:xp-opacity-100 group-hover:xp-opacity-100" : ""}`;

  return (
    <div className="xp-p-[0.2rem] xp-relative xp-group focus:xp-z-10 hover:xp-bg-gray-100">
      <div
        tabIndex={0}
        role="button"
        aria-describedby={headingId}
        className="xp-px-1.5 xp-py-0.5 xp-flex xp-gap-3"
        {...buttonListeners}
      >
        {icon ? (
          <div className="xp-grow-0 xp-shrink-0">
            <div
              className={classNames(
                description ? "xp-w-14 xp-h-14 xp-text-2xl" : "xp-w-8 xp-h-8 xp-text-base",
                "xp-rounded-lg xp-flex xp-text-center xp-items-center xp-justify-center xp-text-white xp-bg-indigo-500",
              )}
              style={{ color: themeFgColor, background: themeBgColor }}
            >
              <IconRenderer icon={icon} />
            </div>
          </div>
        ) : null}
        <div>
          <div id={headingId} className={`xp-flex xp-gap-x-2 xp-items-center xp-flex-wrap`}>
            <div
              className={classNames(
                disabledOpacityClass,
                "xp-text-medium",
                description ? "xp-text-xl xp-leading-tight" : "xp-text-base",
              )}
            >
              {label}
            </div>
            {!isAvailable && availabilityInfo ? <UnavailabilityPills availabilityInfo={availabilityInfo} /> : null}
          </div>
          {description ? (
            <div
              className={classNames(disabledOpacityClass, "xp-text-gray-500")}
              dangerouslySetInnerHTML={{ __html: description }}
            />
          ) : null}
        </div>
      </div>
    </div>
  );
};

const ListEntryHeader = ({ label }: { label: string }) => {
  return (
    <div className="xp-px-[0.2rem] xp-bg-gray-200 xp-mt-2 first:xp-mt-0 xp-sticky xp-top-0 xp-z-10">
      <div className="xp-px-1.5 xp-py-1 xp-text-sm xp-leading-tight xp-font-bold">{label}</div>
    </div>
  );
};

export const PlainResourceList = <T extends Resource>({
  resources,
  onSelect,
  emptyContent,
}: ResourceListProps<T> & { emptyContent?: ReactNode }) => {
  if (!resources.length) {
    return <>{emptyContent || <EmptyResult />}</>;
  }
  return (
    <div className="xp-flex-1 xp-divide-y xp-divide-gray-200">
      {resources.map((o) => {
        return <ListEntry<T> key={`${o.type || ""}${o.name}`} resource={o} onSelect={() => onSelect && onSelect(o)} />;
      })}
    </div>
  );
};

export const LoadingResourceList = () => {
  return (
    <div className="xp-flex-1">
      <div className="xp-h-8 xp-w-full xp-bg-gray-100 xp-mb-2"></div>
      <div className="xp-h-8 xp-w-full xp-bg-gray-100 xp-mb-2"></div>
      <div className="xp-h-8 xp-w-full xp-bg-gray-100 xp-mb-2"></div>
      <div className="xp-h-8 xp-w-full xp-bg-gray-100 xp-mb-2"></div>
      <div className="xp-h-8 xp-w-full xp-bg-gray-100 xp-mb-2"></div>
    </div>
  );
};

export const EmptyResult = ({ message, content }: { message?: ReactNode; content?: ReactNode }) => {
  return (
    <div className="xp-flex-1 xp-flex xp-flex-col xp-items-center xp-justify-center xp-text-center">
      <div>{message || <Str id="noneareavailable" />}</div>
      {content ? <div className="xp-my-2">{content}</div> : null}
    </div>
  );
};
