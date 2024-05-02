import React from "react";

export const ZeroState = ({
  title,
  intro,
  children,
}: {
  title: React.ReactNode;
  intro: React.ReactNode;
  children: React.ReactNode | React.ReactNodeArray;
}) => {
  return (
    <div className="xp-rounded xp-border-dashed xp-border-2 xp-p-4 xp-py-6 xp-text-center xp-border-gray-200">
      <div className="xp-text-xl xp-font-bold xp-mb-4">{title}</div>
      <div>{intro}</div>
      {children ? <div className="xp-mt-4">{children}</div> : null}
    </div>
  );
};
