import React, { cloneElement, useEffect } from "react";
import { getModule } from "../lib/moodle";

export const Popover = ({ children, content }: { children: React.ReactElement; content: string }) => {
  const ref = React.useRef<HTMLElement | null>(null);

  useEffect(() => {
    const $ = getModule("jquery");
    if (!$ || !ref.current || !$(ref.current).popover) {
      return;
    }

    const element = ref.current;
    element.setAttribute("data-container", "body");
    element.setAttribute("data-bs-container", "body");
    element.setAttribute("data-content", content);
    element.setAttribute("data-bs-content", content);
    element.setAttribute("data-html", "true");
    element.setAttribute("data-bs-html", "true");
    element.setAttribute("data-placement", "top");
    element.setAttribute("data-bs-placement", "top");

    const handleBodyClick = (e: MouseEvent) => {
      const target = e.target as HTMLElement;
      if (target.closest(".popover") || element.contains(target)) {
        return;
      }

      try {
        $(element).popover("hide");
      } catch (err) {}
    };

    $(element).popover("enable");
    document.body.addEventListener("click", handleBodyClick);
    return () => {
      document.body.removeEventListener("click", handleBodyClick);
      if (!$(element).popover) {
        return;
      }
      try {
        $(element).popover("dispose");
      } catch (e) {
        try {
          $(element).popover("destroy");
        } catch (e) {}
      }
    };
  }, [content]);

  return cloneElement(children, { ref });
};

export const AnchorPopover = ({
  children,
  className,
  content,
}: {
  children: React.ReactNode;
  className?: string;
  content: string;
}) => {
  return (
    <Popover content={content}>
      <a href="#" role="button" onClick={(e) => e.preventDefault()} className={className}>
        {children}
      </a>
    </Popover>
  );
};
