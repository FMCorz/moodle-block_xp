import React, { useMemo } from "react";
import { useQuery } from "@tanstack/react-query";
import { ajaxRequest } from "../lib/moodle";
import { Resource } from "../lib/types";
import { EmptyResult, LoadingResourceList, PlainResourceList } from "./ResourceList";
import Str from "./Str";
import { CmResourceListProps } from "./CmResourceList";

export const SectionResourceList = ({ courseId, onSelect, options = {} }: CmResourceListProps) => {
  const query = useQuery(["section-resource-list", courseId, options], async () =>
    ajaxRequest<
      {
        name: string;
        number: number;
      }[]
    >("block_xp_get_sections", { courseid: courseId, options })
  );

  const resources = useMemo(() => {
    const data = query.data || [];
    return data.reduce<Resource[]>((carry, section, idx) => {
      carry.push({ name: section.number, label: section.name });
      return carry;
    }, []) as Resource<number>[];
  }, [query.data]);

  if (!query.isSuccess || query.isLoading) {
    return <LoadingResourceList />;
  }

  return (
    <PlainResourceList<Resource<number>>
      resources={resources}
      onSelect={(r) => onSelect(r.name)}
      emptyContent={<EmptyResult message={<Str id="nothingmatchesfilter" />} />}
    />
  );
};
