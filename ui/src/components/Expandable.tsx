import React, { useRef } from "react";
import AnimateHeight from "react-animate-height";
import { getModule } from "../lib/moodle";
import { CorePending } from "../lib/types";

export default function Expandable({ expanded, children, id }: { expanded?: boolean; children: React.ReactNode; id?: string }) {
  const ref = useRef<InstanceType<CorePending> | null>(null);
  return (
    <AnimateHeight
      id={id}
      height={expanded ? "auto" : 0}
      applyInlineTransitions={false}
      onHeightAnimationStart={() => {
        const Pending = getModule<CorePending>("core/pending");
        ref.current?.reject();
        ref.current = Pending ? new Pending("block_xp/expandable") : null;
      }}
      onHeightAnimationEnd={() => {
        ref.current?.resolve();
      }}
      animationStateClasses={{
        animating: "xp-transition-height xp-duration-500",
        static: "xp-transition-height xp-duration-500",
        animatingUp: "",
        animatingDown: "",
        animatingToHeightZero: "",
        animatingToHeightAuto: "",
        animatingToHeightSpecific: "",
        staticHeightZero: "",
        staticHeightAuto: "",
        staticHeightSpecific: "",
      }}
    >
      {children}
    </AnimateHeight>
  );
}
