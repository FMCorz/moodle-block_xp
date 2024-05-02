import React, { useCallback, useContext, useEffect, useMemo, useRef, useState } from "react";
import { AddonContext } from "./contexts";
import { getModuleAsync, getString, hasString, isBehatRunning, loadString, loadStrings } from "./moodle";
import type { CoreModal, CoreModalForm, RuleV2 } from "./types";
import { getUniqueId } from "./utils";

type KnownAmdModules = {
  "block_xp/modal": {
    createSaveCancelModal(config: Record<string, any>): Promise<CoreModal>;
  };
  "core/modal_events": {
    hidden: string;
    save: string;
    shown: string;
  };
  "core_form/modalform": CoreModalForm;
};

type ResolveTypedModule<TModule extends string> = TModule extends keyof KnownAmdModules ? KnownAmdModules[TModule] : any;

export const useAddonActivated = () => {
  return useContext(AddonContext).activated;
};

export const useAddonPromo = () => {
  return useContext(AddonContext).enablepromo;
};

export const useAnchorButtonProps = (onClick: () => void) => {
  const listeners = useRoleButtonListeners(onClick);
  return {
    href: "#",
    role: "button",
    ...listeners,
  };
};

/**
 * Duplication check hook.
 *
 * Usage:
 *
 * const isActionPermitted = useDuplicatedActionPreventor();
 * useEffect(() => {
 *    if (!isActionPermitted()) return;
 * })
 */
export const useDuplicatedActionPreventor = (msDelay = 100) => {
  const ref = useRef<number>();
  return useCallback(() => {
    if (ref.current && ref.current > Date.now() - msDelay) {
      return false;
    }
    ref.current = Date.now();
    return true;
  }, []); // eslint-disable-line
};

export const useModules = <TModules extends readonly string[]>(modules: TModules) => {
  const modulesPromise = useRef<Promise<any>>();
  const modulesRef = useRef<Record<string, any>>();
  const [ready, setReady] = useState(false);

  useEffect(() => {
    if (modulesRef.current) return;

    if (!modulesPromise.current) {
      modulesPromise.current = Promise.all(modules.map((module) => getModuleAsync(module)));
    }

    let cancelled = false;
    modulesPromise.current
      .then((loadedModles) => {
        if (cancelled) return;

        modulesRef.current = modules.reduce(
          (acc, module, i) => {
            acc[module] = loadedModles[i];
            return acc;
          },
          {} as Record<string, any>,
        );

        setReady(true);
        return;
      })
      .catch(() => {});
    return () => {
      cancelled = true;
    };
  });

  const getModule = useCallback(
    <TModule extends TModules[number]>(module: TModule): ResolveTypedModule<TModule> | null => {
      if (!ready || !modulesRef.current) return null;
      return (modulesRef.current[module] ?? null) as ResolveTypedModule<TModule> | null;
    },
    [ready],
  );

  return {
    getModule,
  };
};

export const useNumericInputProps = (value: number, onChange: (n: number) => void) => {
  const valueAsString = value.toString();
  const [externalValue, setExternalValue] = useState(valueAsString);
  const [internalValue, setInternalValue] = useState(externalValue);

  useEffect(() => {
    if (valueAsString !== externalValue) {
      setExternalValue(valueAsString);
      setInternalValue(valueAsString);
    }
  }, [valueAsString, externalValue]);

  const handleBlur = (e: React.FocusEvent<HTMLInputElement>) => {
    const v = parseInt(internalValue, 10) || 0;
    setExternalValue(v.toString());
    onChange(v);
  };

  const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    setInternalValue(e.target.value.replace(/[^0-9]/, ""));
  };

  return {
    value: internalValue,
    onChange: handleChange,
    onBlur: handleBlur,
  };
};

export const useRoleButtonListeners = (onClick: () => void) => {
  const handleClick = (e: React.MouseEvent<HTMLElement>) => {
    e.preventDefault();
    onClick();
  };
  const handleKeyDown = (e: React.KeyboardEvent<HTMLElement>) => {
    if (e.key !== " " && e.key !== "Enter") {
      return;
    }
    e.preventDefault();
    onClick();
  };
  return {
    onClick: handleClick,
    onKeyDown: handleKeyDown,
  };
};

export const useRuleCreationLimit = () => {
  const isAddonActivated = useAddonActivated();
  return !isAddonActivated ? 3 : 0;
};

export const useHasReachedRuleTypeLimit = (rules?: RuleV2[]) => {
  const ruleLimit = useRuleCreationLimit();
  if (ruleLimit <= 0 || !rules) {
    return false;
  }
  return rules?.filter((r) => !["consume_content", "produce_content"].includes(r.typename)).length >= ruleLimit;
};

export const useUnloadCheck = (isDirty: boolean) => {
  const str = useString("changesmadereallygoaway", "core");

  useEffect(() => {
    const fn = (e: BeforeUnloadEvent) => {
      if (!isDirty || isBehatRunning()) {
        return;
      }
      e.preventDefault();
      e.returnValue = str;
      return str;
    };

    window.addEventListener("beforeunload", fn);
    return () => {
      window.removeEventListener("beforeunload", fn);
    };
  });
};

export const useUniqueId = () => {
  const [id] = useState(getUniqueId());
  return id;
};

export const useString = (id: string, component: string = "block_xp", a?: any): string => {
  const wasKnownAtMount = useMemo(() => hasString(id, component), [id, component]);
  const [isLoaded, setLoaded] = useState(false);

  // When the string changes, remove the promise.
  useEffect(() => {
    setLoaded(false);
  }, [id, component]);

  // Load the string when it is unknown.
  useEffect(() => {
    if (wasKnownAtMount || isLoaded) {
      return;
    }

    let cancelled = false;
    (async () => {
      try {
        await loadString(id, component);
        if (!cancelled) {
          setLoaded(true);
        }
      } catch (err) {}
    })();

    return () => {
      cancelled = true;
    };
  });

  return hasString(id, component) ? getString(id, component, a) : "​";
};

export const useStrings = <T extends string>(ids: T[], component: string = "block_xp"): ((id: T, a?: any) => string) => {
  const idsForKey = ids.join(",");
  // eslint-disable-next-line react-hooks/exhaustive-deps
  const allKnownAtMount = useMemo(() => ids.every((id) => hasString(id, component)), [idsForKey, component]);
  const [isLoaded, setLoaded] = useState(false);

  // When the string changes, remove the promise.
  useEffect(() => {
    setLoaded(false);
  }, [idsForKey, component]);

  // Load the string when it is unknown.
  useEffect(() => {
    if (allKnownAtMount || isLoaded) {
      return;
    }

    let cancelled = false;
    (async () => {
      try {
        await loadStrings(ids, component);
        if (!cancelled) {
          setLoaded(true);
        }
      } catch (err) {}
    })();

    return () => {
      cancelled = true;
    };
  });

  return useCallback((id: T, a?: any): string => (hasString(id, component) ? getString(id, component, a) : "​"), [component]);
};
