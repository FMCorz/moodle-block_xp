import React, { useEffect, useMemo, useState } from 'react';
import { getString, hasString, loadString, getModule, loadStrings, isBehatRunning } from './moodle';

export const useUnloadCheck = (isDirty: boolean) => {
  const str = useString('changesmadereallygoaway', 'core');

  useEffect(() => {
    const fn = (e: BeforeUnloadEvent) => {
      if (!isDirty || isBehatRunning()) {
        return;
      }
      e.preventDefault();
      e.returnValue = str;
      return str;
    };

    window.addEventListener('beforeunload', fn);
    return () => {
      window.removeEventListener('beforeunload', fn);
    };
  });
};

export const useString = (id: string, component: string = 'block_xp', a?: any) => {
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

  return hasString(id, component) ? getString(id, component, a) : '';
};

export const useStrings = <T extends string>(ids: T[], component: string = 'block_xp') => {
  const idsForKey = ids.join(',');
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

  return (id: T, a?: any) => (hasString(id, component) ? getString(id, component, a) : '');
};
