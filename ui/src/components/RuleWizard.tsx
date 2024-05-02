import React, { useCallback, useContext, useEffect, useMemo, useState } from "react";
import { AddonContext, RulesSetupContext } from "../lib/contexts";
import { useStrings } from "../lib/hooks";
import { getFilterContentSettings, getTypeThemeColor } from "../lib/rules";
import { RuleConfig, RuleFilter, RuleFilterConfigSettings, RuleType } from "../lib/types";
import { SaveCancelModal } from "./Modal";
import { PlainResourceList } from "./ResourceList";
import { Slide, SlideHeader, Slider } from "./Slider";
import Str from "./Str";
import { RulePointsLimitsForm } from "./rulefilter/RulePointsLimitsForm";

type RenderProps = {
  canNext: boolean;
  nextButtonText: string;
  title: string;
  onCancel: () => void;
  onNext: () => void;
  children: React.ReactNode;
};

export const AddRuleModal = ({
  onSave,
  onClose,
  show,
  selectedType,
}: {
  onSave: (config: RuleConfig) => void;
  onClose: () => void;
  show: boolean;
  selectedType?: string;
}) => {
  const { filters, filtersUsageByType, types } = React.useContext(RulesSetupContext);
  return (
    <RuleWizard
      onSave={onSave}
      onCancel={onClose}
      selectedType={selectedType}
      filters={filters}
      types={types}
      filtersUsageByType={filtersUsageByType}
    >
      {({ onNext, onCancel, children, nextButtonText, title, canNext }) => {
        const handleSave = (e: Event) => {
          e.preventDefault();
          onNext();
        };
        return (
          <SaveCancelModal
            show={show}
            large
            defaultHeight={500}
            onSave={handleSave}
            onClose={onCancel}
            saveButtonText={nextButtonText}
            canSave={canNext}
            title={title}
          >
            {children}
          </SaveCancelModal>
        );
      }}
    </RuleWizard>
  );
};

function RuleWizard({
  children,
  onSave,
  onCancel,
  selectedType: preselectedType,
  autoSelectFilter = false,

  types,
  filters,
  filtersUsageByType,
}: {
  types: Map<string, RuleType>;
  filters: Map<string, RuleFilter>;
  filtersUsageByType: Map<string, string[]>;

  children: (props: RenderProps) => JSX.Element;
  onSave: (config: RuleConfig) => void;
  onCancel: () => void;
  selectedType?: string;

  autoSelectFilter?: boolean;
}) {
  const { enablepromo } = useContext(AddonContext);
  const getStr = useStrings(["addanaction", "addacondition", "rulefilteryalreadyusedbyaction"]);
  const getCoreStr = useStrings(["save", "continue"], "core");

  const [index, setIndex] = useState(0);
  const [selectedType, setSelectedType] = useState<string>();
  const [selectedFilter, setSelectedFilter] = useState<string>();
  const [compatibleFilters, setCompatibleFilters] = useState<RuleFilter[]>([]);
  const [filterSettings, setFilterSettings] = useState<RuleFilterConfigSettings>();
  const [config, setConfig] = useState<RuleConfig>({ points: 10 });
  const filterIsAutomaticallySelected = compatibleFilters.length === 1 && autoSelectFilter;

  const typesAsResources = useMemo(
    () =>
      Array.from(types.values())
        .sort((a, b) => a.label.localeCompare(b.label))
        .map((type) => ({ ...type, themecolor: getTypeThemeColor(type) })),
    [types],
  );
  const hasPreselectedType = Boolean(preselectedType) && types.has(preselectedType!);

  const handleSelectedType = useCallback(
    (type: RuleType) => {
      const ruleFilters = Array.from(filters.values())
        .filter((filter) => types.get(type.name)?.filters.includes(filter.name))
        .filter((filter) => {
          if (
            filter.availabilityinfo?.isavailable === false &&
            filter.availabilityinfo?.reasons.some((r) => r.code === "xpplusrequired") &&
            !enablepromo
          ) {
            return false;
          }
          return true;
        })
        .map((filter) => {
          if (!filter.ismultipleallowed && filtersUsageByType.get(type.name)?.includes(filter.name)) {
            return {
              ...filter,
              availabilityinfo: {
                isavailable: false,
                reasons: [
                  {
                    code: "alreadyused",
                    description: getStr("rulefilteryalreadyusedbyaction"),
                  },
                ],
              },
            };
          }
          return filter;
        })
        .sort((a, b) => a!.label.localeCompare(b!.label)) as RuleFilter[];

      setCompatibleFilters(ruleFilters);
      setSelectedType(type.name);
      const filterToSelect = filterIsAutomaticallySelected ? ruleFilters[0] : null;

      // Code here is mostly a copy of handleSelectedFilter!
      setSelectedFilter(filterToSelect ? filterToSelect.name : undefined);
      setFilterSettings(filterToSelect ? getFilterContentSettings(filterToSelect) : undefined);
      setConfig({ points: 10 });
      setIndex(hasPreselectedType ? 0 : 1);
    },
    [filters, hasPreselectedType, types, filtersUsageByType, filterIsAutomaticallySelected, getStr, enablepromo],
  );

  const handleSelectedFilter = useCallback((filter: RuleFilter) => {
    setSelectedFilter(filter.name);
    setFilterSettings(getFilterContentSettings(filter));
    setConfig({ points: 10 });
    setIndex((index) => index + 1);
  }, []);

  const { canClickSaveButton, isStepContinue } = useMemo(() => {
    const hasFilterSelection = !filterIsAutomaticallySelected;
    const hasFilterContent = selectedFilter && filterSettings?.hasContent;
    const hasPointsStep = !selectedFilter || !filterSettings?.hasContent || !filterSettings?.contentIncludesPoints;

    let steps = ["type", "filter", "filtercontent", "points"];
    if (hasPreselectedType) {
      steps = steps.filter((step) => step !== "type");
    }
    if (!hasFilterSelection) {
      steps = steps.filter((step) => step !== "filter");
    }
    if (!hasFilterContent) {
      steps = steps.filter((step) => step !== "filtercontent");
    }
    if (!hasPointsStep) {
      steps = steps.filter((step) => step !== "points");
    }

    const nSlides = steps.length;
    const isLastStep = selectedType && selectedFilter && index === nSlides - 1;
    const isStepContinue = !isLastStep;

    const currentStep = steps[index];
    const isFilterContentStep = currentStep === "filtercontent";
    const isPointsStep = currentStep === "points";

    let isStepValid = true;
    if (isFilterContentStep) {
      isStepValid = filterSettings?.hasContent ? filterSettings.isConfigValid(config) : true;
    } else if (isPointsStep) {
      isStepValid = typeof config.points === "number" && !isNaN(config.points) && config.points >= 0;
    }

    const canClickSaveButton = Boolean(
      (isLastStep || (isFilterContentStep && filterSettings?.hasContent && filterSettings?.contentRequiresSubmit)) && isStepValid,
    );

    return {
      canClickSaveButton,
      hasFilterSelection,
      hasFilterContent,
      hasPointsStep,
      isFirstStep: index === 0,
      isLastStep,
      isStepContinue,
      isStepValid,
      nSlides,
    };
  }, [hasPreselectedType, config, filterSettings, index, selectedFilter, selectedType, filterIsAutomaticallySelected]);

  const handleBack = useCallback(() => {
    setIndex((index) => Math.max(0, index - 1));
  }, []);

  const handleContinue = useCallback(() => {
    setIndex((index) => index + 1);
  }, []);

  const handleCancel = () => {
    setIndex(0);
    setSelectedType(undefined);
    setSelectedFilter(undefined);
    setFilterSettings(undefined);
    setConfig({ points: 10 });
    onCancel();
  };

  const handleSave = useCallback(() => {
    if (!canClickSaveButton) {
      return;
    }
    if (isStepContinue) {
      setIndex((index) => index + 1);
      return;
    }
    onSave({ ...config, type: selectedType, filter: selectedFilter });
  }, [onSave, selectedType, selectedFilter, isStepContinue, canClickSaveButton, config]);

  const mergeInConfig = useCallback((data: RuleConfig) => setConfig((config) => ({ ...config, ...data })), []);

  // Preselect the type.
  useEffect(() => {
    if (!hasPreselectedType || selectedType === preselectedType) {
      return;
    }
    handleSelectedType(types.get(preselectedType!)!);
  }, [preselectedType, selectedType, types, handleSelectedType, hasPreselectedType]);

  const props = {
    canNext: canClickSaveButton,
    nextButtonText: isStepContinue ? getCoreStr("continue") : getCoreStr("save"),
    title: hasPreselectedType ? getStr("addacondition") : getStr("addanaction"),
    onCancel: handleCancel,
    onNext: handleSave,
    children:
      hasPreselectedType && !selectedType ? null : (
        <Slider index={index}>
          {!hasPreselectedType ? (
            <Slide>
              <PlainResourceList resources={typesAsResources} onSelect={handleSelectedType} />
            </Slide>
          ) : null}
          {!filterIsAutomaticallySelected ? (
            <Slide
              header={
                !hasPreselectedType ? (
                  <SlideHeader
                    hasBack={!hasPreselectedType}
                    onBack={handleBack}
                    title={<Str id="chooseacondition" />}
                    subtitle={types.get(selectedType ?? "")?.label}
                  />
                ) : undefined
              }
            >
              <PlainResourceList resources={compatibleFilters} onSelect={handleSelectedFilter} />
            </Slide>
          ) : null}
          {selectedFilter && filterSettings?.hasContent ? (
            <Slide
              header={
                <SlideHeader
                  hasBack={!filterIsAutomaticallySelected || !hasPreselectedType}
                  onBack={handleBack}
                  title={filters.get(selectedFilter)?.label}
                />
              }
            >
              {filterSettings.getContent({
                config,
                setConfig: mergeInConfig,
                onContinue: handleContinue,
                type: types.get(selectedType ?? "")!,
              })}
            </Slide>
          ) : null}
          {selectedFilter && (!filterSettings?.hasContent || !filterSettings?.contentIncludesPoints) ? (
            <Slide
              header={
                <SlideHeader
                  hasBack
                  onBack={handleBack}
                  title={filterSettings?.hasContent ? <Str id="pointstoaward" /> : filters.get(selectedFilter)?.label}
                />
              }
            >
              <RulePointsLimitsForm
                config={config}
                setConfig={mergeInConfig}
                ruleType={types.get(selectedType ?? "")!}
                filterName={selectedFilter ?? ""}
              />
            </Slide>
          ) : null}
        </Slider>
      ),
  };

  return children(props);
}
