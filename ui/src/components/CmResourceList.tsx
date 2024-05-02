import React, { useMemo } from "react";
import { useQuery } from "@tanstack/react-query";
import { getModuleAsync } from "../lib/moodle";
import { Resource } from "../lib/types";
import { Button } from "./Button";
import { EmptyResult, LoadingResourceList, PlainResourceList } from "./ResourceList";
import Str from "./Str";

export type CmResourceListProps = {
  courseId: number;
  filterTerm?: string;
  onSelect: (cmid: number) => void;
  resetFilterTerm?: () => void;
  options?: { completionenabled?: boolean; type?: string };
};

export const CmResourceList = ({ courseId, filterTerm, onSelect, resetFilterTerm, options = {} }: CmResourceListProps) => {
  const query = useQuery(["cm-resource-list", courseId, options], async () => {
    const Ajax = await getModuleAsync("core/ajax");
    return (await Ajax.call([
      {
        methodname: "block_xp_search_modules",
        args: { courseid: courseId, query: "*", options },
      },
    ])[0]) as Promise<
      [
        {
          name: string; // The section name.
          modules: {
            cmid: number;
            contextid: number;
            name: string;
          }[];
        }
      ]
    >;
  });

  const resources = useMemo(() => {
    const normalisedFilterTerm = (filterTerm || "").trim().toLowerCase();
    const data = query.data || [];
    return data.reduce<Resource[]>((carry, section, idx) => {
      const modules =
        normalisedFilterTerm === ""
          ? section.modules
          : section.modules.filter((module) => {
              return module.name.includes(normalisedFilterTerm);
            });

      if (!modules.length) {
        return carry;
      }

      // Only show headers if we have multiple sections.
      if (data.length > 1) {
        carry.push({ name: idx, label: section.name, type: "header" });
      }

      modules.forEach((module) => {
        carry.push({ name: module.cmid, label: module.name });
      });
      return carry;
    }, []) as Resource<number>[];
  }, [query.data, filterTerm]);

  if (!query.isSuccess || query.isLoading) return <LoadingResourceList />;

  return (
    <PlainResourceList<Resource<number>>
      resources={resources}
      onSelect={(r) => onSelect(r.name)}
      emptyContent={
        <EmptyResult
          message={<Str id="nothingmatchesfilter" />}
          content={
            resetFilterTerm ? (
              <Button onClick={resetFilterTerm}>
                <Str id="clearfilter" />
              </Button>
            ) : null
          }
        />
      }
    />
  );
};
