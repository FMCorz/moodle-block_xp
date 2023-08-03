import React, { cloneElement, useEffect } from "react";
import { getModule } from "../lib/moodle";

export const Tooltip: React.FC<{ children: React.ReactElement; content: string }> = ({ children, content }) => {
  const ref = React.useRef<HTMLElement | null>(null);

  useEffect(() => {
    const $ = getModule("jquery");
    if (!$ || !ref.current || !$(ref.current).tooltip) {
      return;
    }

    ref.current.setAttribute("data-toggle", "popover");
    ref.current.setAttribute("data-container", "body");
    ref.current.setAttribute("title", content);

    $(ref.current).tooltip("enable");
    return () => {
      $(ref.current).tooltip("dispose");
    };
  }, [content]);

  return cloneElement(children, { ref });
};
