/* eslint-disable */
/* Do not edit directly, refer to ui/ folder. */
define(["block_xp/ui-commons-lazy"],() => { return /******/ (() => { // webpackBootstrap
/******/ 	"use strict";
/******/ 	var __webpack_modules__ = ({

/***/ 8917
(__unused_webpack_module, __webpack_exports__, __webpack_require__) {

// ESM COMPAT FLAG
__webpack_require__.r(__webpack_exports__);

// EXPORTS
__webpack_require__.d(__webpack_exports__, {
  App: () => (/* binding */ App),
  dependencies: () => (/* binding */ dependencies),
  startApp: () => (/* binding */ startApp)
});

// EXTERNAL MODULE: ./node_modules/@headlessui/react/dist/components/tabs/tabs.js + 2 modules
var tabs = __webpack_require__(1848);
// EXTERNAL MODULE: ./node_modules/react/index.js
var react = __webpack_require__(6540);
// EXTERNAL MODULE: ./node_modules/react-dom/client.js
var client = __webpack_require__(5338);
// EXTERNAL MODULE: ./node_modules/@tanstack/react-query/build/lib/QueryClientProvider.mjs
var QueryClientProvider = __webpack_require__(3064);
;// ./ui/src/components/Loading.tsx

const AppLoading = () => {
    return (react.createElement("div", { className: "block_xp-react-loading" },
        react.createElement("div", { className: "xp-grid xp-grid-cols-2 xp-gap-4 xp-animate-pulse" },
            react.createElement("div", { className: "xp-col-span-2 xp-bg-gray-100 xp-rounded xp-h-4" }),
            react.createElement("div", { className: "xp-bg-gray-100 xp-rounded xp-h-4" }),
            react.createElement("div", { className: "xp-bg-gray-100 xp-rounded xp-h-4" }))));
};

// EXTERNAL MODULE: ./node_modules/react-dom/index.js
var react_dom = __webpack_require__(961);
;// ./ui/src/lib/utils.ts
const classNames = (...args) => args.filter(Boolean).join(" ");
const escapeCharMap = {
    '&': '&amp;',
    '<': '&lt;',
    '>': '&gt;',
    '"': '&quot;',
    "'": '&#039;'
};
const escapeHtml = (text) => {
    return text.replace(/[&<>"']/g, function (m) { return escapeCharMap[m]; });
};
const fifoCache = (maxItems = 128) => {
    let items = {};
    let keys = [];
    const purge = () => {
        if (keys.length > maxItems) {
            const idx = Math.max(0, keys.length - maxItems);
            keys.slice(0, idx).forEach((key) => {
                delete items[key];
            });
            keys = keys.slice(idx);
        }
    };
    return {
        set: (key, value) => {
            items[key] = value;
            keys.push(key);
            purge();
        },
        get: (key) => {
            return items[key];
        },
    };
};
let uniqueId = 0;
const getUniqueId = () => {
    return `xp-${Date.now()}-${uniqueId++}`;
};
const groupBy = (arr, key) => {
    const map = new Map();
    for (const entry of arr) {
        const index = entry[key];
        if (!map.has(index)) {
            map.set(index, []);
        }
        map.set(index, (map.get(index) ?? []).concat(entry));
    }
    return map;
};
const mapFrom = (arr, key) => {
    return new Map(arr.map(entry => ([entry[key], entry])));
};
const stripTags = (html) => {
    var tmp = document.createElement("div");
    tmp.innerHTML = html;
    return tmp.textContent || tmp.innerText || "";
};
const uniq = (arr) => {
    return arr.filter((value, index, self) => self.indexOf(value) === index);
};

;// ./ui/src/lib/moodle.ts

const M = window.M;
const modules = {};
/**
 * List of modules that we currently depend on statically.
 *
 * Preferrably, modules should be loaded with getModuleAsync, which
 * does not require their definition to be declared in our apps.
 */
const commonStaticModulesToDependOn = ["core/notification", "core/aria", "core/pending", "?core/toast", "jquery"];
async function ajaxRequest(method, args) {
    const Ajax = await getModuleAsync("core/ajax");
    return Ajax.call([
        {
            methodname: method,
            args,
        },
    ])[0];
}
function getString(id, component, a) {
    return M.util.get_string(id, component, a);
}
function getUrl(uri, searchParams) {
    const url = new URL(uri, M.cfg.wwwroot);
    if (searchParams) {
        url.search = searchParams.toString();
    }
    return url.toString();
}
function hasString(id, component) {
    // eslint-disable-next-line no-restricted-properties
    return typeof M.str[component] !== "undefined" && typeof M.str[component][id] !== "undefined";
}
function getModule(name) {
    return modules[name];
}
async function getModuleAsync(amd) {
    if (modules[amd]) {
        return modules[amd];
    }
    return new Promise((resolve, reject) => {
        window.require([amd], (mod) => {
            modules[amd] = mod;
            resolve(mod);
        }, reject);
    });
}
function imageUrl(name, component) {
    return M.util.image_url(name, component);
}
function isBehatRunning() {
    return M.cfg.behatsiterunning;
}
const loadStringCache = fifoCache(64);
async function loadString(id, component) {
    const cacheKey = `${id}/${component}`;
    let promise = loadStringCache.get(cacheKey);
    if (!promise) {
        const Str = await getModuleAsync("core/str");
        promise = Str.get_string(id, component);
        loadStringCache.set(cacheKey, promise);
    }
    return await promise;
}
async function loadStrings(ids, component) {
    const cacheKey = `${ids.join(",")}/${component}`;
    let promise = loadStringCache.get(cacheKey);
    if (!promise) {
        const Str = await getModuleAsync("core/str");
        promise = Str.get_strings(ids.map((id) => ({ key: id, component })));
        loadStringCache.set(cacheKey, promise);
    }
    return await promise;
}
const makeDependenciesDefinition = (names) => {
    const optional = [];
    const list = names.map((name) => {
        const isOptional = name.charAt(0) === "?";
        const module = isOptional ? name.substring(1) : name;
        if (isOptional) {
            optional.push(module);
        }
        return module;
    });
    return {
        list,
        optional,
        loader: (mods) => {
            mods.forEach((mod, i) => {
                setModule(list[i], mod);
            });
        },
    };
};
function setModule(name, mod) {
    modules[name] = mod;
}

;// ./ui/src/lib/types.ts
var ContextLevel;
(function (ContextLevel) {
    ContextLevel[ContextLevel["System"] = 10] = "System";
    ContextLevel[ContextLevel["User"] = 30] = "User";
    ContextLevel[ContextLevel["CourseCategory"] = 40] = "CourseCategory";
    ContextLevel[ContextLevel["Course"] = 50] = "Course";
    ContextLevel[ContextLevel["Module"] = 70] = "Module";
})(ContextLevel || (ContextLevel = {}));
var LimitSpecTimeWindow;
(function (LimitSpecTimeWindow) {
    LimitSpecTimeWindow[LimitSpecTimeWindow["NONE"] = 0] = "NONE";
    LimitSpecTimeWindow[LimitSpecTimeWindow["ONEHOUR"] = 1] = "ONEHOUR";
    LimitSpecTimeWindow[LimitSpecTimeWindow["DAILY"] = 2] = "DAILY";
    LimitSpecTimeWindow[LimitSpecTimeWindow["WEEKLY"] = 3] = "WEEKLY";
    LimitSpecTimeWindow[LimitSpecTimeWindow["MONTHLY"] = 4] = "MONTHLY";
    LimitSpecTimeWindow[LimitSpecTimeWindow["HOUR"] = 5] = "HOUR";
})(LimitSpecTimeWindow || (LimitSpecTimeWindow = {}));
var LimitSpecScope;
(function (LimitSpecScope) {
    LimitSpecScope[LimitSpecScope["None"] = 0] = "None";
    LimitSpecScope[LimitSpecScope["Env"] = 2] = "Env";
    LimitSpecScope[LimitSpecScope["Parent"] = 4] = "Parent";
    LimitSpecScope[LimitSpecScope["Object"] = 8] = "Object";
})(LimitSpecScope || (LimitSpecScope = {}));
var RuleTypeGoal;
(function (RuleTypeGoal) {
    RuleTypeGoal["Comms"] = "comms";
    RuleTypeGoal["Contrib"] = "contrib";
    RuleTypeGoal["Read"] = "read";
    RuleTypeGoal["Assess"] = "assess";
})(RuleTypeGoal || (RuleTypeGoal = {}));
var RuleTypeProfileSubject;
(function (RuleTypeProfileSubject) {
    RuleTypeProfileSubject["Cm"] = "cm";
    RuleTypeProfileSubject["Section"] = "section";
    RuleTypeProfileSubject["Course"] = "course";
})(RuleTypeProfileSubject || (RuleTypeProfileSubject = {}));

;// ./ui/src/lib/contexts.ts



const defaultMoodleContext = {
    id: 0,
    contextlevel: ContextLevel.System,
    instanceid: 0,
};
const AddonContext = (0,react.createContext)({
    activated: false,
    enablepromo: true,
    promourl: "https://www.levelup.plus/xp/", // Local promo page where possible.
});
const RulesSetupContext = (0,react.createContext)({
    addRule: () => { },
    editRule: (id) => { },
    removeRule: (id) => { },
    context: defaultMoodleContext,
    types: new Map(),
    filters: new Map(),
    filtersUsageByType: new Map(),
});
const WorldContext = (0,react.createContext)({
    context: defaultMoodleContext,
    courseid: 0,
    navigateTo: () => { },
});
const makeAddonContextValueFromAppProps = (props) => {
    return {
        activated: false,
        enablepromo: true,
        promourl: "https://www.levelup.plus/xp/",
        ...(props?.addon ?? {}),
    };
};
const makeWorldContextValueFromAppProps = ({ world }) => {
    const courseId = world?.courseid ?? (world?.contextlevel === ContextLevel.Course ? (world?.contextinstanceid ?? 0) : 0);
    const resolveRoute = (routeName, params) => {
        // Shallow implementation, does not support all kinds of routes.
        return getUrl(`/blocks/xp/index.php/${routeName}/${courseId}`, new URLSearchParams(Object.entries(params ?? {}).map(([key, value]) => [key, value?.toString() ?? ""])));
    };
    const navigateTo = (route, params) => {
        window.location.href = resolveRoute(route, params);
    };
    return {
        context: {
            id: world?.contextid ?? 0,
            contextlevel: world?.contextlevel ?? ContextLevel.System,
            instanceid: courseId,
        },
        courseid: courseId,
        navigateTo,
    };
};

;// ./ui/src/lib/hooks.ts
/* unused harmony import specifier */ var useContext;
/* unused harmony import specifier */ var useEffect;
/* unused harmony import specifier */ var hooks_AddonContext;
/* unused harmony import specifier */ var hooks_isBehatRunning;




const useAddonActivated = () => {
    return (0,react.useContext)(AddonContext).activated;
};
const useAddonPromo = () => {
    return useContext(hooks_AddonContext).enablepromo;
};
const useAnchorButtonProps = (onClick) => {
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
const useDuplicatedActionPreventor = (msDelay = 100) => {
    const ref = (0,react.useRef)();
    return (0,react.useCallback)(() => {
        if (ref.current && ref.current > Date.now() - msDelay) {
            return false;
        }
        ref.current = Date.now();
        return true;
    }, []); // eslint-disable-line
};
const useModules = (modules) => {
    const modulesPromise = (0,react.useRef)();
    const modulesRef = (0,react.useRef)();
    const [ready, setReady] = (0,react.useState)(false);
    (0,react.useEffect)(() => {
        if (modulesRef.current)
            return;
        if (!modulesPromise.current) {
            modulesPromise.current = Promise.all(modules.map((module) => getModuleAsync(module)));
        }
        let cancelled = false;
        modulesPromise.current
            .then((loadedModles) => {
            if (cancelled)
                return;
            modulesRef.current = modules.reduce((acc, module, i) => {
                acc[module] = loadedModles[i];
                return acc;
            }, {});
            setReady(true);
            return;
        })
            .catch(() => { });
        return () => {
            cancelled = true;
        };
    });
    const getModule = (0,react.useCallback)((module) => {
        if (!ready || !modulesRef.current)
            return null;
        return (modulesRef.current[module] ?? null);
    }, [ready]);
    return {
        getModule,
    };
};
const useNumericInputProps = (value, onChange) => {
    const valueAsString = value.toString();
    const [externalValue, setExternalValue] = (0,react.useState)(valueAsString);
    const [internalValue, setInternalValue] = (0,react.useState)(externalValue);
    (0,react.useEffect)(() => {
        if (valueAsString !== externalValue) {
            setExternalValue(valueAsString);
            setInternalValue(valueAsString);
        }
    }, [valueAsString, externalValue]);
    const handleBlur = (e) => {
        const v = parseInt(internalValue, 10) || 0;
        setExternalValue(v.toString());
        onChange(v);
    };
    const handleChange = (e) => {
        setInternalValue(e.target.value.replace(/[^0-9]/, ""));
    };
    return {
        value: internalValue,
        onChange: handleChange,
        onBlur: handleBlur,
    };
};
const useRoleButtonListeners = (onClick) => {
    const handleClick = (e) => {
        e.preventDefault();
        onClick();
    };
    const handleKeyDown = (e) => {
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
const useRuleCreationLimit = () => {
    const isAddonActivated = useAddonActivated();
    return !isAddonActivated ? 3 : 0;
};
const useHasReachedRuleTypeLimit = (rules) => {
    const ruleLimit = useRuleCreationLimit();
    if (ruleLimit <= 0 || !rules) {
        return false;
    }
    return rules?.filter((r) => !["consume_content", "produce_content"].includes(r.typename)).length >= ruleLimit;
};
const useUnloadCheck = (isDirty) => {
    const str = useString("changesmadereallygoaway", "core");
    useEffect(() => {
        const fn = (e) => {
            if (!isDirty || hooks_isBehatRunning()) {
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
const useUniqueId = () => {
    const [id] = (0,react.useState)(getUniqueId());
    return id;
};
const useString = (id, component = "block_xp", a) => {
    const wasKnownAtMount = (0,react.useMemo)(() => hasString(id, component), [id, component]);
    const [isLoaded, setLoaded] = (0,react.useState)(false);
    // When the string changes, remove the promise.
    (0,react.useEffect)(() => {
        setLoaded(false);
    }, [id, component]);
    // Load the string when it is unknown.
    (0,react.useEffect)(() => {
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
            }
            catch (err) { }
        })();
        return () => {
            cancelled = true;
        };
    });
    return hasString(id, component) ? getString(id, component, a) : "​";
};
const useStrings = (ids, component = "block_xp") => {
    const idsForKey = ids.join(",");
    // eslint-disable-next-line react-hooks/exhaustive-deps
    const allKnownAtMount = (0,react.useMemo)(() => ids.every((id) => hasString(id, component)), [idsForKey, component]);
    const [isLoaded, setLoaded] = (0,react.useState)(false);
    // When the string changes, remove the promise.
    (0,react.useEffect)(() => {
        setLoaded(false);
    }, [idsForKey, component]);
    // Load the string when it is unknown.
    (0,react.useEffect)(() => {
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
            }
            catch (err) { }
        })();
        return () => {
            cancelled = true;
        };
    });
    return (0,react.useCallback)((id, a) => (hasString(id, component) ? getString(id, component, a) : "​"), [component]);
};

;// ./ui/src/components/Modal.tsx



function getModalButton(modal, action) {
    if (!modal)
        return null;
    const btnJq = modal.getFooter().find(modal.getActionSelector(action));
    return btnJq.length ? btnJq[0] : null;
}
function getModalFormButton(modalForm, action) {
    return getModalButton(modalForm?.modal, action);
}
const SaveCancelModal = ({ children, onClose, onSave, show, title, saveButtonText, defaultHeight, large, canSave = true, }) => {
    const modalPromise = (0,react.useRef)();
    const modalRef = (0,react.useRef)();
    // In rare instances, we can get double save events. This can happen when we hit enter,
    // and a new event listener is registered while Moodle is still broadcasting its events
    // which is then called, and so we get two events. This wouldn't happen if the modal was
    // not re-rendering, I think.
    const isSavePermitted = useDuplicatedActionPreventor();
    const { getModule } = useModules(["block_xp/modal", "core/modal_events"]);
    const [ready, setReady] = (0,react.useState)(false);
    const setSaveButtonText = (text) => {
        const saveBtn = getModalButton(modalRef.current, "save");
        if (!saveBtn || !text)
            return;
        saveBtn.textContent = text;
    };
    const setButtonAttribute = (attr, value) => {
        const saveBtn = getModalButton(modalRef.current, "save");
        if (!saveBtn || !attr)
            return;
        if (value === null || typeof value === "undefined" || value === false) {
            saveBtn.removeAttribute(attr);
        }
        else {
            saveBtn.setAttribute(attr, value);
        }
    };
    // Create the modal object.
    (0,react.useEffect)(() => {
        let cancelled = false;
        if (modalRef.current)
            return;
        const Modal = getModule("block_xp/modal");
        if (!Modal)
            return;
        if (!modalPromise.current) {
            modalPromise.current = Modal.createSaveCancelModal({
                title: title,
                large: large,
                body: `<div class='block_xp' style='${defaultHeight ? `height: ${defaultHeight}px` : ""}'></div>`,
            });
        }
        modalPromise.current
            .then((modal) => {
            if (cancelled)
                return;
            modalRef.current = modal;
            if (saveButtonText) {
                setSaveButtonText(saveButtonText);
            }
            setReady(true); // State update to force re-render.
            if (show) {
                modal.show();
            }
            return;
        })
            .catch(() => {
            return;
        });
        return () => {
            cancelled = true;
        };
    });
    // Attach event listeners.
    (0,react.useEffect)(() => {
        const modal = modalRef.current;
        if (!modal)
            return;
        const ModalEvents = getModule("core/modal_events");
        if (!ModalEvents)
            return;
        const root = modal.getRoot();
        const handleSave = (e) => {
            if (!isSavePermitted())
                return;
            onSave && onSave(e);
        };
        const handleClose = () => {
            onClose && onClose();
        };
        // Keep the React node height in sync with the modal body to avoid for the modal
        // to become scrollable. This is required because our current modal content is
        // absolute and thus requires a hardcoded height.
        const updateReactNodeHeight = () => {
            const body = modal.getBody()[0];
            const reactNode = body ? body.querySelector(".block_xp") : null;
            if (!body || !reactNode) {
                return;
            }
            const height = body.clientHeight - (parseFloat(getComputedStyle(body).paddingTop) + parseFloat(getComputedStyle(body).paddingBottom));
            reactNode.style.height = `${height}px`;
        };
        const attachResize = () => {
            window.addEventListener("resize", updateReactNodeHeight);
        };
        root.on(ModalEvents.save, handleSave);
        root.on(ModalEvents.hidden, handleClose);
        root.on(ModalEvents.shown, attachResize);
        return () => {
            root.off(ModalEvents.save, handleSave);
            root.off(ModalEvents.hidden, handleClose);
            root.off(ModalEvents.shown, attachResize);
            window.removeEventListener("resize", updateReactNodeHeight);
        };
    });
    // Update visibility.
    (0,react.useEffect)(() => {
        if (!modalRef.current)
            return;
        if (show) {
            modalRef.current.show();
        }
        else {
            modalRef.current.hide();
        }
    }, [show, modalRef.current]); // eslint-disable-line react-hooks/exhaustive-deps
    // Update title.
    (0,react.useEffect)(() => {
        if (!modalRef.current || !title)
            return;
        modalRef.current.setTitle(title);
    }, [title, modalRef.current]); // eslint-disable-line react-hooks/exhaustive-deps
    // Update save button text.
    (0,react.useEffect)(() => {
        setSaveButtonText(saveButtonText);
    }, [saveButtonText, modalRef.current]); // eslint-disable-line react-hooks/exhaustive-deps
    // Update the save button status.
    (0,react.useEffect)(() => {
        setButtonAttribute("disabled", !canSave);
    }, [canSave, modalRef.current]); // eslint-disable-line react-hooks/exhaustive-deps
    return (react.createElement(react.Fragment, null, modalRef.current
        ? react_dom.createPortal(children, modalRef.current.getBody()[0].querySelector(".block_xp"))
        : null));
};
const DeleteModal = ({ children, onClose, onDelete, show, title, }) => {
    const modalPromise = (0,react.useRef)();
    const modalRef = (0,react.useRef)();
    const [ready, setReady] = (0,react.useState)(false);
    const isDeletePermitted = useDuplicatedActionPreventor();
    const deleteStr = useString("delete", "core");
    const { getModule } = useModules(["block_xp/modal", "core/modal_events"]);
    // Create the modal object.
    (0,react.useEffect)(() => {
        let cancelled = false;
        if (modalRef.current)
            return;
        const Modal = getModule("block_xp/modal");
        if (!Modal)
            return;
        if (!modalPromise.current) {
            modalPromise.current = Modal.createSaveCancelModal({
                title: title,
                body: `<div class='block_xp'></div>`,
            });
        }
        modalPromise.current
            .then((modal) => {
            if (cancelled)
                return;
            modalRef.current = modal;
            const deleteButton = getModalButton(modal, "save");
            if (deleteButton) {
                if (deleteStr) {
                    deleteButton.textContent = deleteStr;
                }
                deleteButton.classList.add("btn-danger");
            }
            setReady(true); // State update to force re-render.
            if (show) {
                modal.show();
            }
            return;
        })
            .catch(() => {
            return;
        });
        return () => {
            cancelled = true;
        };
    });
    // Attach event listeners.
    (0,react.useEffect)(() => {
        const modal = modalRef.current;
        if (!modal)
            return;
        const ModalEvents = getModule("core/modal_events");
        if (!ModalEvents)
            return;
        const root = modal.getRoot();
        const handleSave = (e) => {
            if (!isDeletePermitted())
                return;
            onDelete && onDelete(e);
        };
        const handleClose = () => {
            onClose && onClose();
        };
        root.on(ModalEvents.save, handleSave);
        root.on(ModalEvents.hidden, handleClose);
        return () => {
            root.off(ModalEvents.save, handleSave);
            root.off(ModalEvents.hidden, handleClose);
        };
    });
    // Update visibility.
    (0,react.useEffect)(() => {
        if (!modalRef.current)
            return;
        if (show) {
            modalRef.current.show();
        }
        else {
            modalRef.current.hide();
        }
    }, [show, modalRef.current]); // eslint-disable-line react-hooks/exhaustive-deps
    // Update title.
    (0,react.useEffect)(() => {
        if (!modalRef.current || !title)
            return;
        modalRef.current.setTitle(title);
    }, [title, modalRef.current]); // eslint-disable-line react-hooks/exhaustive-deps
    // Update button.
    (0,react.useEffect)(() => {
        if (!modalRef.current || !deleteStr)
            return;
        const btn = getModalButton(modalRef.current, "save");
        if (!btn)
            return;
        btn.textContent = deleteStr;
    }, [deleteStr, modalRef.current]); // eslint-disable-line react-hooks/exhaustive-deps
    return (react.createElement(react.Fragment, null, modalRef.current
        ? react_dom.createPortal(children, modalRef.current.getBody()[0].querySelector(".block_xp"))
        : null));
};
const ModalForm = ({ formClass, formArgs, onClose, onSubmit, saveButtonDisabled, title, }) => {
    const modalFormRef = (0,react.useRef)();
    const { getModule } = useModules(["core_form/modalform", "core/modal_events"]);
    // Create the modal form.
    (0,react.useEffect)(() => {
        if (modalFormRef.current)
            return;
        const ModalForm = getModule("core_form/modalform");
        if (!ModalForm)
            return;
        modalFormRef.current = new ModalForm({
            formClass: formClass,
            args: formArgs ?? {},
            modalConfig: {
                title,
            },
        });
        modalFormRef.current.show();
    });
    // Attach event listeners.
    (0,react.useEffect)(() => {
        const modalForm = modalFormRef.current;
        if (!modalForm)
            return;
        const ModalForm = getModule("core_form/modalform");
        const ModalEvents = getModule("core/modal_events");
        if (!ModalForm || !ModalEvents)
            return;
        const handleLoaded = () => {
            const root = modalForm.modal.getRoot();
            root[0].classList.add("block_xp");
            if (saveButtonDisabled) {
                getModalFormButton(modalForm, "save")?.setAttribute("disabled", "");
            }
            // Register the onClose event.
            root.on(ModalEvents.hidden, handleClose);
        };
        const handleSubmit = () => {
            onSubmit && onSubmit();
        };
        const handleClose = () => {
            onClose && onClose();
        };
        modalForm.addEventListener(modalForm.events.LOADED, handleLoaded);
        modalForm.addEventListener(modalForm.events.FORM_SUBMITTED, handleSubmit);
        modalForm.addEventListener(modalForm.events.CANCEL_BUTTON_PRESSED, handleClose);
        return () => {
            const modalForm = modalFormRef.current;
            if (!modalForm)
                return;
            const root = modalForm.modal.getRoot();
            const rootEl = root?.[0];
            rootEl?.removeEventListener(modalForm.events.LOADED, handleLoaded);
            rootEl?.removeEventListener(modalForm.events.FORM_SUBMITTED, handleSubmit);
            rootEl?.removeEventListener(modalForm.events.CANCEL_BUTTON_PRESSED, handleClose);
            root.off(ModalEvents.hidden, handleClose);
        };
    });
    (0,react.useEffect)(() => {
        if (!modalFormRef.current)
            return;
        const modal = modalFormRef.current.modal;
        if (!modal)
            return;
        modal.setTitle(title ?? "");
    }, [title]);
    return null;
};

;// ./ui/src/components/Notification.tsx

const NotificationError = ({ children }) => {
    return react.createElement("div", { className: "alert alert-danger" }, children);
};

// EXTERNAL MODULE: ./node_modules/@tanstack/react-query/build/lib/useQuery.mjs
var useQuery = __webpack_require__(9421);
;// ./ui/src/components/Pix.tsx
/* unused harmony import specifier */ var React;
/* unused harmony import specifier */ var Pix_imageUrl;


const Pix = ({ id, component = "block_xp", className, alt = "", }) => {
    return React.createElement("img", { src: Pix_imageUrl(id, component), alt: alt, className: className });
};
/* harmony default export */ const components_Pix = ((/* unused pure expression or super */ null && (Pix)));

;// ./ui/src/components/Spinner.tsx
/* unused harmony import specifier */ var Spinner_React;
/* unused harmony import specifier */ var Spinner_useString;
/* unused harmony import specifier */ var Spinner_Pix;



const Spinner = ({ className }) => {
    const alt = Spinner_useString("loadinghelp", "core");
    return Spinner_React.createElement(Spinner_Pix, { id: "y/loading", component: "core", className: className, alt: alt });
};
/* harmony default export */ const components_Spinner = ((/* unused pure expression or super */ null && (Spinner)));

;// ./ui/src/components/Str.tsx


const Str = ({ id, component = "block_xp", a }) => {
    const str = useString(id, component, a);
    return react.createElement(react.Fragment, null, str || "​");
};
/* harmony default export */ const components_Str = (Str);

;// ./ui/src/components/Button.tsx
/* unused harmony import specifier */ var Button_React;
/* unused harmony import specifier */ var Button_useStrings;
/* unused harmony import specifier */ var Button_useAnchorButtonProps;
/* unused harmony import specifier */ var Button_Pix;
/* unused harmony import specifier */ var Button_Spinner;
/* unused harmony import specifier */ var Button_Str;
/* unused harmony import specifier */ var Button_classNames;






const CircleButton = ({ className, ...props }) => {
    return (react.createElement("button", { className: classNames("xp-bg-transparent xp-border-0 xp-p-2 xp-flex xp-items-center xp-rounded-full xp-duration-150 xp-transition-colors", "hover:xp-bg-gray-200", className), type: "button", ...props }));
};
const Button = ({ onClick, disabled, children, primary, outline, className, type = "button", }) => {
    const classes = classNames("btn", primary ? `btn-${outline ? "outline-" : ""}primary` : `btn-default btn-${outline ? "outline-" : ""}secondary`, className);
    return (react.createElement("button", { className: classes, onClick: onClick, disabled: disabled, type: type }, children));
};
const ExpandCollapseButton = ({ expanded, onToggle, ariaControlsId, }) => {
    return (Button_React.createElement(AnchorButton, { "aria-expanded": expanded, "aria-controls": ariaControlsId, onClick: onToggle, className: "xp-p-2 xp-inline-block sm:xp-mr-1" },
        Button_React.createElement("span", { className: "xp-sr-only" }, expanded ? Button_React.createElement(Button_Str, { id: "collapse", component: "core" }) : Button_React.createElement(Button_Str, { id: "expand", component: "core" })),
        Button_React.createElement("svg", { xmlns: "http://www.w3.org/2000/svg", fill: "none", viewBox: "0 0 24 24", strokeWidth: 1.5, stroke: "currentColor", className: Button_classNames("xp-w-6 xp-h-6 xp-transition-transform xp-duration-300", expanded ? "xp-rotate-90" : null) },
            Button_React.createElement("path", { strokeLinecap: "round", strokeLinejoin: "round", d: "M8.25 4.5l7.5 7.5-7.5 7.5" }))));
};
const SaveButton = ({ onClick, disabled, label, mutation = {}, statePosition = "after", }) => {
    const getStr = Button_useStrings(["changessaved", "error"], "core");
    const { isLoading, isSuccess, isError } = mutation;
    const isStateBefore = statePosition === "before";
    const state = (Button_React.createElement("div", { className: `xp-w-8 xp-flex ${isStateBefore ? "xp-mr-4 xp-justify-end" : "xp-ml-4"}`, "aria-live": "assertive" },
        isLoading ? Button_React.createElement(Button_Spinner, null) : null,
        isSuccess ? Button_React.createElement(Button_Pix, { id: "i/valid", component: "core", alt: getStr("changessaved") }) : null,
        isError ? Button_React.createElement(Button_Pix, { id: "i/invalid", component: "core", alt: getStr("error") }) : null));
    return (Button_React.createElement("div", { className: "xp-flex xp-items-center" },
        isStateBefore ? state : null,
        Button_React.createElement("div", { className: "" },
            Button_React.createElement(Button, { primary: true, onClick: onClick, disabled: disabled || isLoading }, label || Button_React.createElement(Button_Str, { id: "savechanges", component: "core" }))),
        !isStateBefore ? state : null));
};
const AnchorButton = ({ children, onClick, className, ...props }) => {
    const anchorButtonProps = Button_useAnchorButtonProps(onClick);
    return (Button_React.createElement("a", { className: Button_classNames("xp-text-inherit xp-no-underline", className), ...props, ...anchorButtonProps }, children));
};

;// ./ui/src/components/Icons.tsx
/* unused harmony import specifier */ var Icons_React;

const IconRenderer = ({ icon }) => {
    if (icon.type === "fa") {
        return react.createElement("i", { className: `fa fa-${icon.value}` });
    }
    return null;
};
const Bars3BottomLeftIcon = ({ className }) => (Icons_React.createElement("svg", { xmlns: "http://www.w3.org/2000/svg", viewBox: "0 0 24 24", fill: "currentColor", className: className },
    Icons_React.createElement("path", { fillRule: "evenodd", d: "M3 6.75A.75.75 0 013.75 6h16.5a.75.75 0 010 1.5H3.75A.75.75 0 013 6.75zM3 12a.75.75 0 01.75-.75h16.5a.75.75 0 010 1.5H3.75A.75.75 0 013 12zm0 5.25a.75.75 0 01.75-.75H12a.75.75 0 010 1.5H3.75a.75.75 0 01-.75-.75z", clipRule: "evenodd" })));
const CheckBadgeIconSolid = ({ className }) => (Icons_React.createElement("svg", { xmlns: "http://www.w3.org/2000/svg", viewBox: "0 0 24 24", fill: "currentColor", className: className },
    Icons_React.createElement("path", { fillRule: "evenodd", d: "M8.603 3.799A4.49 4.49 0 0112 2.25c1.357 0 2.573.6 3.397 1.549a4.49 4.49 0 013.498 1.307 4.491 4.491 0 011.307 3.497A4.49 4.49 0 0121.75 12a4.49 4.49 0 01-1.549 3.397 4.491 4.491 0 01-1.307 3.497 4.491 4.491 0 01-3.497 1.307A4.49 4.49 0 0112 21.75a4.49 4.49 0 01-3.397-1.549 4.49 4.49 0 01-3.498-1.306 4.491 4.491 0 01-1.307-3.498A4.49 4.49 0 012.25 12c0-1.357.6-2.573 1.549-3.397a4.49 4.49 0 011.307-3.497 4.49 4.49 0 013.497-1.307zm7.007 6.387a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z", clipRule: "evenodd" })));
const LanguageIcon = ({ className }) => (Icons_React.createElement("svg", { xmlns: "http://www.w3.org/2000/svg", viewBox: "0 0 24 24", fill: "currentColor", className: className },
    Icons_React.createElement("path", { fillRule: "evenodd", d: "M9 2.25a.75.75 0 01.75.75v1.506a49.38 49.38 0 015.343.371.75.75 0 11-.186 1.489c-.66-.083-1.323-.151-1.99-.206a18.67 18.67 0 01-2.969 6.323c.317.384.65.753.998 1.107a.75.75 0 11-1.07 1.052A18.902 18.902 0 019 13.687a18.823 18.823 0 01-5.656 4.482.75.75 0 11-.688-1.333 17.323 17.323 0 005.396-4.353A18.72 18.72 0 015.89 8.598a.75.75 0 011.388-.568A17.21 17.21 0 009 11.224a17.17 17.17 0 002.391-5.165 48.038 48.038 0 00-8.298.307.75.75 0 01-.186-1.489 49.159 49.159 0 015.343-.371V3A.75.75 0 019 2.25zM15.75 9a.75.75 0 01.68.433l5.25 11.25a.75.75 0 01-1.36.634l-1.198-2.567h-6.744l-1.198 2.567a.75.75 0 01-1.36-.634l5.25-11.25A.75.75 0 0115.75 9zm-2.672 8.25h5.344l-2.672-5.726-2.672 5.726z", clipRule: "evenodd" })));
const PaperAirplaneIconSolid = ({ className }) => (Icons_React.createElement("svg", { xmlns: "http://www.w3.org/2000/svg", viewBox: "0 0 24 24", fill: "currentColor", className: className },
    Icons_React.createElement("path", { d: "M3.478 2.405a.75.75 0 00-.926.94l2.432 7.905H13.5a.75.75 0 010 1.5H4.984l-2.432 7.905a.75.75 0 00.926.94 60.519 60.519 0 0018.445-8.986.75.75 0 000-1.218A60.517 60.517 0 003.478 2.405z" })));
const ChevronLeftIconSolid = ({ className }) => (react.createElement("svg", { xmlns: "http://www.w3.org/2000/svg", fill: "none", viewBox: "0 0 24 24", strokeWidth: 1.5, stroke: "currentColor", className: className },
    react.createElement("path", { strokeLinecap: "round", strokeLinejoin: "round", d: "M15.75 19.5 8.25 12l7.5-7.5" })));

;// ./ui/src/components/ResourceList.tsx





const UnavailabilityPills = ({ availabilityInfo }) => {
    const getStr = useStrings(["unavailable", "alreadyused", "xpplusrequired"]);
    return (react.createElement(react.Fragment, null, availabilityInfo.reasons.map((ai, idx) => {
        let desc = getStr("unavailable");
        let badgeType = "badge-warning";
        if (ai.code === "xpplusrequired") {
            badgeType = "badge-dark";
            desc = getStr("xpplusrequired");
        }
        else if (ai.code === "xppremiumrequired") {
            badgeType = "badge-dark";
            desc = "XP+ Premium";
        }
        else if (ai.code === "alreadyused") {
            badgeType = "badge-primary";
            desc = getStr("alreadyused");
        }
        return (react.createElement("span", { key: `${ai.code}-${idx}`, className: classNames("badge badge-pill", badgeType) }, desc));
    })));
};
const ListEntry = ({ resource, onSelect }) => {
    if (resource.type === "header") {
        return react.createElement(ListEntryHeader, { label: resource.label });
    }
    return (react.createElement(ListEntryItem, { label: resource.label, description: resource.description, availabilityInfo: resource?.availabilityinfo, icon: resource.icon, themeColor: resource.themecolor, onSelect: onSelect }));
};
const ListEntryItem = ({ label, description, availabilityInfo, onSelect, icon, themeColor, }) => {
    const [themeFgColor, themeBgColor] = themeColor || [];
    const headingId = useUniqueId();
    const buttonListeners = useRoleButtonListeners(onSelect);
    const isAvailable = availabilityInfo?.isavailable ?? true;
    const disabledOpacityClass = `${!isAvailable ? "xp-opacity-60 group-focus:xp-opacity-100 group-hover:xp-opacity-100" : ""}`;
    return (react.createElement("div", { className: "xp-p-[0.2rem] xp-relative xp-group focus:xp-z-10 hover:xp-bg-gray-100" },
        react.createElement("div", { tabIndex: 0, role: "button", "aria-describedby": headingId, className: "xp-px-1.5 xp-py-0.5 xp-flex xp-gap-3", ...buttonListeners },
            icon ? (react.createElement("div", { className: "xp-grow-0 xp-shrink-0" },
                react.createElement("div", { className: classNames(description ? "xp-w-14 xp-h-14 xp-text-2xl" : "xp-w-8 xp-h-8 xp-text-base", "xp-rounded-lg xp-flex xp-text-center xp-items-center xp-justify-center xp-text-white xp-bg-indigo-500"), style: { color: themeFgColor, background: themeBgColor } },
                    react.createElement(IconRenderer, { icon: icon })))) : null,
            react.createElement("div", null,
                react.createElement("div", { id: headingId, className: `xp-flex xp-gap-x-2 xp-items-center xp-flex-wrap` },
                    react.createElement("div", { className: classNames(disabledOpacityClass, "xp-text-medium", description ? "xp-text-xl xp-leading-tight" : "xp-text-base") }, label),
                    !isAvailable && availabilityInfo ? react.createElement(UnavailabilityPills, { availabilityInfo: availabilityInfo }) : null),
                description ? (react.createElement("div", { className: classNames(disabledOpacityClass, "xp-text-gray-500"), dangerouslySetInnerHTML: { __html: description } })) : null))));
};
const ListEntryHeader = ({ label }) => {
    return (react.createElement("div", { className: "xp-px-[0.2rem] xp-bg-gray-200 xp-mt-2 first:xp-mt-0 xp-sticky xp-top-0 xp-z-10" },
        react.createElement("div", { className: "xp-px-1.5 xp-py-1 xp-text-sm xp-leading-tight xp-font-bold" }, label)));
};
const PlainResourceList = ({ resources, onSelect, emptyContent, }) => {
    if (!resources.length) {
        return react.createElement(react.Fragment, null, emptyContent || react.createElement(EmptyResult, null));
    }
    return (react.createElement("div", { className: "xp-flex-1 xp-divide-y xp-divide-gray-200" }, resources.map((o) => {
        return react.createElement(ListEntry, { key: `${o.type || ""}${o.name}`, resource: o, onSelect: () => onSelect && onSelect(o) });
    })));
};
const LoadingResourceList = () => {
    return (react.createElement("div", { className: "xp-flex-1" },
        react.createElement("div", { className: "xp-h-8 xp-w-full xp-bg-gray-100 xp-mb-2" }),
        react.createElement("div", { className: "xp-h-8 xp-w-full xp-bg-gray-100 xp-mb-2" }),
        react.createElement("div", { className: "xp-h-8 xp-w-full xp-bg-gray-100 xp-mb-2" }),
        react.createElement("div", { className: "xp-h-8 xp-w-full xp-bg-gray-100 xp-mb-2" }),
        react.createElement("div", { className: "xp-h-8 xp-w-full xp-bg-gray-100 xp-mb-2" })));
};
const EmptyResult = ({ message, content }) => {
    return (react.createElement("div", { className: "xp-flex-1 xp-flex xp-flex-col xp-items-center xp-justify-center xp-text-center" },
        react.createElement("div", null, message || react.createElement(components_Str, { id: "noneareavailable" })),
        content ? react.createElement("div", { className: "xp-my-2" }, content) : null));
};

;// ./ui/src/components/CmResourceList.tsx






const CmResourceList = ({ courseId, filterTerm, onSelect, resetFilterTerm, options = {} }) => {
    const query = (0,useQuery/* useQuery */.I)(["cm-resource-list", courseId, options], async () => {
        const Ajax = await getModuleAsync("core/ajax");
        return (await Ajax.call([
            {
                methodname: "block_xp_search_modules",
                args: { courseid: courseId, query: "*", options },
            },
        ])[0]);
    });
    const resources = (0,react.useMemo)(() => {
        const normalisedFilterTerm = (filterTerm || "").trim().toLowerCase();
        const data = query.data || [];
        return data.reduce((carry, section, idx) => {
            const modules = normalisedFilterTerm === ""
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
        }, []);
    }, [query.data, filterTerm]);
    if (!query.isSuccess || query.isLoading)
        return react.createElement(LoadingResourceList, null);
    return (react.createElement(PlainResourceList, { resources: resources, onSelect: (r) => onSelect(r.name), emptyContent: react.createElement(EmptyResult, { message: react.createElement(components_Str, { id: "nothingmatchesfilter" }), content: resetFilterTerm ? (react.createElement(Button, { onClick: resetFilterTerm },
                react.createElement(components_Str, { id: "clearfilter" }))) : null }) }));
};

;// ./ui/src/components/rulefilter/cm.tsx




const Content = (props) => {
    const { context } = (0,react.useContext)(RulesSetupContext);
    return (react.createElement(CmResourceList, { courseId: context.contextlevel === ContextLevel.Course ? context.instanceid : 0, options: {
            completionenabled: props.type.profile?.requirescompletionenabled,
            type: props.type.profile?.cmtype ?? undefined,
        }, onSelect: (cmid) => {
            props.setConfig({ filtercmid: cmid });
            props.onContinue();
        } }));
};
const cmConfigSettings = {
    hasContent: true,
    getContent: (props) => react.createElement(Content, { ...props }),
    isConfigValid: (config) => Boolean(config.filtercmid),
    contentIncludesPoints: false,
    contentRequiresSubmit: false,
};

;// ./ui/src/components/Input.tsx
/* unused harmony import specifier */ var Input_React;

const Input = ({ className = "", ...props }) => {
    /** Apply those classes for normalised styling across themes and versions. */
    return react.createElement("input", { ...props, className: `xp-m-0 form-control ${className}` });
};
const Select = ({ className = "", ...props }) => {
    /** Apply those classes for normalised styling across themes and versions. */
    return react.createElement("select", { ...props, className: `xp-m-0 xp-max-w-auto form-select form-control ${className}` });
};
const Textarea = ({ className = "", ...props }) => {
    /** Apply those classes for normalised styling across themes and versions. */
    return Input_React.createElement("textarea", { ...props, className: `xp-m-0 form-control ${className}` });
};
const FieldHelp = ({ children }) => {
    return react.createElement("p", { className: "xp-text-gray-500 xp-m-0 xp-mt-1" }, children);
};
/* harmony default export */ const components_Input = (Input);

;// ./ui/src/lib/rulelimits.ts

/** Mirrors {@see \block_xp\form\rule::get_default_data()} limit fields when the rule type has defaults. */
function getInitialLimitFieldsFromRuleType(ruleType) {
    const dl = ruleType.defaultlimit;
    const dr = ruleType.defaultrepeatlimit;
    if (!dl || !dr) {
        return {
            limitmax: 0,
            limitwindow: LimitSpecTimeWindow.NONE,
            repeatscope: LimitSpecScope.None,
            repeatwindow: LimitSpecTimeWindow.NONE,
        };
    }
    const isUnlimited = dr.max === 0;
    return {
        limitmax: dl.max,
        limitwindow: dl.timewindow,
        repeatscope: isUnlimited ? LimitSpecScope.None : dr.scope,
        repeatwindow: isUnlimited ? dl.timewindow : dr.timewindow,
    };
}
function ruleTypeSupportsLimits(ruleType) {
    return (ruleType.defaultlimit !== null &&
        ruleType.defaultlimit !== undefined &&
        ruleType.defaultrepeatlimit !== null &&
        ruleType.defaultrepeatlimit !== undefined);
}

;// ./ui/src/components/Addon.tsx





const IfAddonActivatedOrPromoEnabled = ({ children }) => {
    const { activated, enablepromo } = (0,react.useContext)(AddonContext);
    if (!activated && !enablepromo) {
        return null;
    }
    return react.createElement(react.Fragment, null, children);
};
const IfAddonPromoEnabled = ({ children }) => {
    const { activated, enablepromo } = (0,react.useContext)(AddonContext);
    if (activated || !enablepromo) {
        return null;
    }
    return react.createElement(react.Fragment, null, children);
};
const AddonRequired = (props) => {
    const { promourl } = (0,react.useContext)(AddonContext);
    const getStr = useStrings(["xpplusrequired", "unlockfeaturewithxpplus"]);
    const handleClick = (e) => e.preventDefault();
    const ref = (0,react.useRef)(null);
    (0,react.useEffect)(() => {
        const handleClick = (e) => {
            const $ = getModule("jquery");
            if (!$ || !ref.current || !$(ref.current).popover) {
                return;
            }
            const target = e.target;
            if (target.closest(".popover")) {
                return;
            }
            else if (ref.current.contains(target)) {
                return;
            }
            try {
                $(ref.current).popover("hide");
            }
            catch (err) { }
        };
        document.body.addEventListener("click", handleClick);
        return () => document.body.removeEventListener("click", handleClick);
    });
    return (react.createElement("a", { ref: ref, href: "#", role: "button", onClick: handleClick, "data-bs-toggle": "popover", "data-toggle": "popover", "data-placement": "top", "data-container": "body", "data-content": getStr("unlockfeaturewithxpplus", promourl), "data-bs-content": getStr("unlockfeaturewithxpplus", promourl), "data-html": "true", "data-bs-html": "true", className: "xp-py-1 xp-px-1.5 xp-normal-case xp-text-2xs xp-inline-block xp-bg-black xp-text-white xp-rounded xp-no-underline" }, props.children ? props.children : getStr("xpplusrequired")));
};
const AddonRequiredShort = () => {
    return react.createElement(AddonRequired, null, "XP+");
};
const AddonTag = () => {
    return (react.createElement("span", { className: classNames("xp-py-0.5 xp-px-1 xp-normal-case xp-text-2xs xp-inline-block xp-bg-black xp-text-white", "xp-rounded xp-no-underline xp-font-normal xp-align-middle xp-select-none") }, "XP+"));
};

;// ./ui/src/components/NumberInput.tsx
/* unused harmony import specifier */ var NumberInput_React;
/* unused harmony import specifier */ var NumberInput_useNumericInputProps;




const NumInput = ({ className, value, onChange, selectOnFocus, ...props }) => {
    const inputProps = useNumericInputProps(value, onChange);
    const handleFocus = (e) => {
        if (!selectOnFocus)
            return;
        e.currentTarget.select();
    };
    return react.createElement(components_Input, { type: "text", ...inputProps, className: className, onFocus: handleFocus, ...props });
};
const PlainNumberInput = ({ value, onChange, selectOnFocus, ...props }) => {
    const inputProps = NumberInput_useNumericInputProps(value, onChange);
    const handleFocus = (e) => {
        if (!selectOnFocus)
            return;
        e.currentTarget.select();
    };
    return NumberInput_React.createElement("input", { type: "text", ...inputProps, onFocus: handleFocus, ...props });
};
const NumberInputWithButtons = ({ onChange, value, min, max, suffix, step = 1, inputProps, }) => {
    const hasMin = typeof min !== "undefined";
    const hasMax = typeof max !== "undefined";
    const minDisabled = hasMin && min >= value;
    const maxDisabled = hasMax && max <= value;
    const minusProps = useAnchorButtonProps(() => {
        if (minDisabled)
            return;
        handleChange(value - step);
    });
    const plusProps = useAnchorButtonProps(() => {
        if (maxDisabled)
            return;
        handleChange(value + step);
    });
    const handleChange = (n) => {
        let final = n;
        if (hasMin) {
            final = Math.max(min, final);
        }
        if (hasMax) {
            final = Math.min(max, final);
        }
        onChange(final);
    };
    const { className: inputClassName, ...remainingInputProps } = inputProps ?? {};
    const allInputProps = {
        className: classNames("xp-h-auto xp-border-0 xp-text-center xp-rounded-none focus:xp-z-10", suffix ? "xp-pr-6" : null, inputClassName || "xp-w-16"),
        ...remainingInputProps,
    };
    return (react.createElement("div", { className: "xp-inline-flex xp-rounded xp-border xp-border-solid xp-border-gray-300" },
        react.createElement("a", { ...minusProps, className: classNames("xp-flex-0 xp-border-0 xp-border-gray-300 xp-border-solid xp-border-r xp-rounded-l xp-py-0.5 xp-px-1", "xp-flex xp-items-center xp-justify-center", "focus:xp-z-10", minDisabled ? "xp-bg-gray-100 xp-cursor-pointer xp-text-gray-500" : "xp-bg-white xp-text-inherit") },
            react.createElement("svg", { xmlns: "http://www.w3.org/2000/svg", viewBox: "0 0 20 20", fill: "currentColor", className: "xp-w-5 xp-h-5" },
                react.createElement("path", { fillRule: "evenodd", d: "M4 10a.75.75 0 01.75-.75h10.5a.75.75 0 010 1.5H4.75A.75.75 0 014 10z", clipRule: "evenodd" }))),
        react.createElement("div", { className: "xp-flex-1 xp-relative" },
            react.createElement(NumInput, { onChange: handleChange, value: value, ...allInputProps }),
            suffix ? (react.createElement("div", { className: "xp-pointer-events-none xp-absolute xp-inset-y-0 xp-right-0 xp-flex xp-items-center xp-pr-2" },
                react.createElement("span", { className: "xp-text-gray-500" }, suffix))) : null),
        react.createElement("a", { ...plusProps, className: classNames("xp-flex-0 xp-border-0 xp-border-gray-300 xp-border-solid xp-border-l xp-rounded-r xp-py-0.5 xp-px-1", "xp-flex xp-items-center xp-justify-center", "focus:xp-z-10", maxDisabled ? "xp-bg-gray-100 xp-cursor-pointer xp-text-gray-500" : "xp-bg-white xp-text-inherit") },
            react.createElement("svg", { xmlns: "http://www.w3.org/2000/svg", viewBox: "0 0 20 20", fill: "currentColor", className: "xp-w-5 xp-h-5" },
                react.createElement("path", { d: "M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" })))));
};

;// ./ui/src/components/rulefilter/inputs.tsx




const PointsToAwardInput = ({ setConfig, config }) => {
    return (react.createElement("div", null,
        react.createElement("label", { htmlFor: "xp-rule-pointstoaward", className: "xp-m-0" },
            react.createElement(components_Str, { id: "pointstoaward" })),
        react.createElement("div", null,
            react.createElement(NumberInputWithButtons, { value: config.points ?? 10, onChange: (points) => setConfig({ ...config, points }), min: 0, max: 9999999, inputProps: { id: "xp-rule-pointstoaward", className: "xp-w-24", selectOnFocus: true } })),
        react.createElement(FieldHelp, null,
            react.createElement(components_Str, { id: "pointstoaward_help" }))));
};

;// ./ui/src/components/rulefilter/RulePointsLimitsForm.tsx







function filterRepeatLimitOptionsForFilter(options, filterName) {
    return options?.filter((opt) => !opt.incompatiblewithfilters?.includes(filterName)) ?? [];
}
const RulePointsLimitsForm = ({ config, filterName, ruleType, setConfig, }) => {
    const isActivated = useAddonActivated();
    const getStr = useStrings([
        "forever",
        "intotal",
        "nolimit",
        "ntimes",
        "once",
        "overalllimit",
        "overalllimitdesc",
        "perday",
        "perhour",
        "permonth",
        "perweek",
        "pointstoaward",
        "pointstoaward_help",
        "repeatsallowed",
        "repetitionlimit",
        "repetitionlimitdesc",
        "repetitiontimeframe",
        "timesallowed",
        "timeframe",
        "unknown",
        "unlimitedrepeats",
        "usedefaultlimits",
    ]);
    const repeatOptions = (0,react.useMemo)(() => filterRepeatLimitOptionsForFilter(ruleType.repeatlimitoptions, filterName), [filterName, ruleType.repeatlimitoptions]);
    const useDefaultLimits = config.usedefaultlimits ?? true;
    const handleToggleDefaultLimits = (0,react.useCallback)((e) => {
        setConfig({ ...config, usedefaultlimits: e.currentTarget.checked });
    }, [config, setConfig]);
    const defaultLimitFields = (0,react.useMemo)(() => getInitialLimitFieldsFromRuleType(ruleType), [ruleType]);
    const limitMax = config.limitmax ?? defaultLimitFields.limitmax;
    const limitWindow = config.limitwindow ?? defaultLimitFields.limitwindow;
    const repeatScope = config.repeatscope ?? defaultLimitFields.repeatscope;
    const repeatWindow = config.repeatwindow ?? defaultLimitFields.repeatwindow;
    return (react.createElement("div", { className: "xp-space-y-4" },
        react.createElement(PointsToAwardInput, { config: config, setConfig: setConfig }),
        react.createElement(If, { condition: ruleTypeSupportsLimits(ruleType) && (config.points ?? 1) > 0 },
            react.createElement(IfAddonActivatedOrPromoEnabled, null,
                react.createElement("div", { className: "form-check" },
                    react.createElement("input", { type: "checkbox", id: "xp-rule-usedefaultlimits", className: "form-check-input", checked: useDefaultLimits, onChange: handleToggleDefaultLimits }),
                    react.createElement("label", { className: "form-check-label", htmlFor: "xp-rule-usedefaultlimits" }, getStr("usedefaultlimits"))),
                react.createElement(If, { condition: !useDefaultLimits },
                    react.createElement("div", { className: "" },
                        react.createElement("div", { className: "xp-flex xp-items-center xp-gap-2 xp-leading-none xp-mb-1" },
                            react.createElement("div", null, getStr("overalllimit")),
                            react.createElement(IfAddonPromoEnabled, null,
                                react.createElement("div", null,
                                    react.createElement(AddonRequiredShort, null)))),
                        react.createElement("div", { className: "xp-flex xp-flex-wrap xp-gap-x-1 xp-items-end" },
                            react.createElement(MaxTimes, { value: limitMax, onChange: (v) => setConfig({ ...config, limitmax: v }) }),
                            react.createElement(If, { condition: Boolean(limitMax) },
                                react.createElement(TimeFrame, { value: limitWindow, onChange: (v) => setConfig({ ...config, limitwindow: v }), label: getStr("timeframe"), noneLabel: getStr("intotal") }))),
                        react.createElement(FieldHelp, null, getStr("overalllimitdesc"))),
                    react.createElement(If, { condition: repeatOptions.length > 0 },
                        react.createElement("div", { className: "" },
                            react.createElement("div", { className: "xp-flex xp-items-center xp-gap-2 xp-leading-none xp-mb-1" },
                                react.createElement("div", null, getStr("repetitionlimit")),
                                react.createElement(IfAddonPromoEnabled, null,
                                    react.createElement("div", null,
                                        react.createElement(AddonRequiredShort, null)))),
                            react.createElement("div", { className: "xp-flex xp-flex-wrap xp-gap-x-1 xp-items-end" },
                                react.createElement("div", { className: "" },
                                    react.createElement("label", { htmlFor: "xp-rule-repeatscope", className: "xp-sr-only xp-m-0" }, getStr("repeatsallowed")),
                                    react.createElement("div", { className: "sm:xp-max-w-56" },
                                        react.createElement(Select, { id: "xp-rule-repeatscope", value: repeatScope, onChange: (e) => setConfig({ ...config, repeatscope: parseInt(e.currentTarget.value, 10) }) },
                                            react.createElement("option", { value: LimitSpecScope.None, disabled: repeatScope !== LimitSpecScope.None && !isActivated }, getStr("unlimitedrepeats")),
                                            repeatOptions.map((opt) => (react.createElement("option", { key: opt.value, value: opt.value, disabled: opt.value != repeatScope && !isActivated }, opt.oncelabel)))))),
                                react.createElement(If, { condition: repeatScope !== LimitSpecScope.None },
                                    react.createElement(TimeFrame, { value: repeatWindow, onChange: (v) => setConfig({ ...config, repeatwindow: v }), label: getStr("repetitiontimeframe"), noneLabel: getStr("forever") }))),
                            react.createElement(FieldHelp, null, getStr("repetitionlimitdesc")))))))));
};
const If = ({ condition, children }) => {
    if (!condition) {
        return null;
    }
    return react.createElement(react.Fragment, null, children);
};
const MaxTimes = ({ value, onChange }) => {
    const getStr = useStrings(["nolimit", "once", "ntimes", "timesallowed"]);
    const isActivated = useAddonActivated();
    return (react.createElement("div", { className: "" },
        react.createElement("label", { htmlFor: "xp-rule-limitmax", className: "xp-sr-only xp-m-0" }, getStr("timesallowed")),
        react.createElement("div", { className: "sm:xp-max-w-56" },
            react.createElement(Select, { id: "xp-rule-limitmax", value: value, onChange: (e) => onChange(parseInt(e.currentTarget.value, 10)) },
                react.createElement("option", { value: 0, disabled: value != 0 && !isActivated }, getStr("nolimit")),
                react.createElement("option", { value: 1, disabled: value != 1 && !isActivated }, getStr("once")),
                [2, 3, 5, 7, 10, 12, 15, 20, 50, 100].map((n) => (react.createElement("option", { key: n, value: n, disabled: value != n && !isActivated }, getStr("ntimes", n))))))));
};
const TimeFrame = ({ value, onChange, label, noneLabel, }) => {
    const id = useUniqueId();
    const getStr = useStrings(["perhour", "perday", "perweek", "permonth"]);
    const isActivated = useAddonActivated();
    const handleChange = (0,react.useCallback)((e) => {
        onChange(parseInt(e.currentTarget.value, 10));
    }, [onChange]);
    return (react.createElement("div", null,
        react.createElement("label", { htmlFor: id, className: "xp-sr-only xp-m-0" }, label),
        react.createElement("div", { className: "sm:xp-max-w-56" },
            react.createElement(Select, { id: id, value: value, onChange: handleChange },
                react.createElement("option", { value: LimitSpecTimeWindow.HOUR, disabled: value != LimitSpecTimeWindow.HOUR && !isActivated }, getStr("perhour")),
                react.createElement("option", { value: LimitSpecTimeWindow.DAILY, disabled: value != LimitSpecTimeWindow.DAILY && !isActivated }, getStr("perday")),
                react.createElement("option", { value: LimitSpecTimeWindow.WEEKLY, disabled: value != LimitSpecTimeWindow.WEEKLY && !isActivated }, getStr("perweek")),
                react.createElement("option", { value: LimitSpecTimeWindow.MONTHLY, disabled: value != LimitSpecTimeWindow.MONTHLY && !isActivated }, getStr("permonth")),
                react.createElement("option", { value: LimitSpecTimeWindow.NONE, disabled: value != LimitSpecTimeWindow.NONE && !isActivated }, noneLabel)))));
};

;// ./ui/src/components/rulefilter/cmname.tsx





const cmNameConfigSettings = {
    hasContent: true,
    getContent: (props) => react.createElement(CmNameContent, { ...props }),
    isConfigValid: (config) => [0, 1].includes(config?.filterint1 ?? -1) &&
        typeof config.filterchar1 === "string" &&
        config.filterchar1.trim() !== "" &&
        typeof config?.points === "number" &&
        !isNaN(config.points),
    contentIncludesPoints: true,
    contentRequiresSubmit: true,
};
const CmNameContent = ({ config, setConfig, type }) => {
    const defaultValue = 1;
    const getStr = useStrings(["rule:eq", "rule:contains", "rulefiltercmname"]);
    return (react.createElement(react.Fragment, null,
        react.createElement("div", { className: "xp-mb-4" },
            react.createElement("label", { htmlFor: "xp-rule-cmname-name", className: "xp-m-0" },
                react.createElement(components_Str, { id: "activityname" })),
            react.createElement("div", { className: "xp-flex xp-gap-2" },
                react.createElement("label", { htmlFor: "xp-rule-cmname-method", className: "xp-sr-only" },
                    react.createElement(components_Str, { id: "comparisonmethod" })),
                react.createElement(Select, { id: "xp-rule-cmname-method", value: config.filterint1, onChange: (e) => setConfig({ filterint1: parseInt(e.currentTarget.value, 10) || 0 }), defaultValue: defaultValue.toString(), className: "xp-w-auto" },
                    react.createElement("option", { value: "1" }, getStr("rule:contains")),
                    react.createElement("option", { value: "0" }, getStr("rule:eq"))),
                react.createElement(components_Input, { id: "xp-rule-cmname-name", value: config.filterchar1 || "", onChange: (e) => setConfig({ filterchar1: e.currentTarget.value, filterint1: config.filterint1 ?? defaultValue }), maxLength: 255 })),
            react.createElement("p", { className: "xp-text-gray-500 xp-m-0 xp-mt-1" },
                react.createElement(components_Str, { id: "activityname_help" }))),
        react.createElement(RulePointsLimitsForm, { config: config, setConfig: setConfig, ruleType: type, filterName: "cmname" })));
};

;// ./ui/src/components/rulefilter/cmtag.tsx




const cmtagConfigSettings = {
    hasContent: true,
    getContent: (props) => react.createElement(CmtagContent, { ...props }),
    isConfigValid: (config) => typeof config.filterchar1 === "string" &&
        config.filterchar1.trim() !== "" &&
        typeof config?.points === "number" &&
        !isNaN(config.points),
    contentIncludesPoints: true,
    contentRequiresSubmit: true,
};
const CmtagContent = ({ config, setConfig, type }) => {
    return (react.createElement(react.Fragment, null,
        react.createElement("div", { className: "xp-mb-4" },
            react.createElement("label", { htmlFor: "xp-rule-cmtag-name", className: "xp-m-0" },
                react.createElement(components_Str, { id: "rulefiltercmtagfield" })),
            react.createElement(components_Input, { id: "xp-rule-cmtag-name", value: config.filterchar1 || "", onChange: (e) => setConfig({ filterchar1: e.currentTarget.value }), maxLength: 255 }),
            react.createElement(FieldHelp, null,
                react.createElement(components_Str, { id: "rulefiltercmtaghelp" }))),
        react.createElement(RulePointsLimitsForm, { config: config, setConfig: setConfig, ruleType: type, filterName: "cmtag" })));
};

;// ./ui/src/components/SectionResourceList.tsx





const SectionResourceList = ({ courseId, onSelect, options = {} }) => {
    const query = (0,useQuery/* useQuery */.I)(["section-resource-list", courseId, options], async () => ajaxRequest("block_xp_get_sections", { courseid: courseId, options }));
    const resources = (0,react.useMemo)(() => {
        const data = query.data || [];
        return data.reduce((carry, section, idx) => {
            carry.push({ name: section.number, label: section.name });
            return carry;
        }, []);
    }, [query.data]);
    if (!query.isSuccess || query.isLoading) {
        return react.createElement(LoadingResourceList, null);
    }
    return (react.createElement(PlainResourceList, { resources: resources, onSelect: (r) => onSelect(r.name), emptyContent: react.createElement(EmptyResult, { message: react.createElement(components_Str, { id: "nothingmatchesfilter" }) }) }));
};

;// ./ui/src/components/rulefilter/section.tsx




const section_Content = (props) => {
    const { context } = (0,react.useContext)(RulesSetupContext);
    return (react.createElement(SectionResourceList, { courseId: context.contextlevel === ContextLevel.Course ? context.instanceid : 0, options: {}, onSelect: (sectionNum) => {
            props.setConfig({ filterint1: sectionNum });
            props.onContinue();
        } }));
};
const sectionConfigSettings = {
    hasContent: true,
    getContent: (props) => react.createElement(section_Content, { ...props }),
    isConfigValid: (config) => typeof config.filterint1 === "number",
    contentIncludesPoints: false,
    contentRequiresSubmit: false,
};

;// ./ui/src/components/UnavailableContent.tsx


const UnavailableContent = ({ availabilityInfo }) => {
    return (react.createElement(react.Fragment, null,
        react.createElement("p", null,
            react.createElement(components_Str, { id: "unavailablebecause" })),
        react.createElement("ul", null, availabilityInfo?.reasons.map((ai, idx) => {
            return react.createElement("li", { key: `${ai.code}-${idx}` }, ai.description);
        }))));
};

;// ./ui/src/components/rulefilter/unavailable.tsx


const getUnavailableConfigSettings = (filter) => {
    return {
        hasContent: true,
        getContent: () => react.createElement(UnavailableContent, { availabilityInfo: filter.availabilityinfo }),
        contentIncludesPoints: false,
        contentRequiresSubmit: true,
        isConfigValid: () => false,
    };
};

;// ./ui/src/components/rulefilter/unknown.tsx


const getUnknownConfigSettings = (filter) => {
    return {
        hasContent: true,
        getContent: (props) => react.createElement(UnknownContent, { filter: filter }),
        contentIncludesPoints: false,
        contentRequiresSubmit: true,
        isConfigValid: () => false,
    };
};
const UnknownContent = ({ filter }) => {
    return (react.createElement(react.Fragment, null,
        react.createElement("p", null,
            react.createElement(components_Str, { id: "unknowntypea", a: filter.name }))));
};

;// ./ui/src/lib/rules.ts










const noSettings = { hasContent: false };
const specialTypes = ["consume_content", "produce_content"];
function getFilterContentSettings(filter) {
    if (!(filter.availabilityinfo?.isavailable ?? true)) {
        return getUnavailableConfigSettings(filter);
    }
    if (filter.name === "cmname") {
        return cmNameConfigSettings;
    }
    else if (filter.name === "cmtag") {
        return cmtagConfigSettings;
    }
    else if (filter.name === "cm") {
        return cmConfigSettings;
    }
    else if (filter.name === "section") {
        return sectionConfigSettings;
    }
    else if (filter.name.startsWith("any") || filter.name === "thiscourse") {
        return noSettings;
    }
    return getUnknownConfigSettings(filter);
}
function getTypeThemeColor(type) {
    switch (type.goal) {
        case RuleTypeGoal.Assess:
            return ["#fff", "#f43f5e"];
        case RuleTypeGoal.Comms:
            return ["#fff", "#a855f7"];
        case RuleTypeGoal.Contrib:
            return ["#fff", "#22c55e"];
        case RuleTypeGoal.Read:
            return ["#fff", "#3b82f6"];
    }
    return undefined;
}
const useMakeRulesSetupContext = (props) => {
    const typesByName = (0,react.useMemo)(() => {
        return mapFrom(props.ruletypes, "name");
    }, [props.ruletypes]);
    const filtersByName = (0,react.useMemo)(() => {
        return mapFrom(props.rulefilters, "name");
    }, [props.rulefilters]);
    return {
        types: typesByName,
        filters: filtersByName,
    };
};
const useRulesInfo = (query, deleted) => {
    const limit = useRuleCreationLimit();
    const rules = (0,react.useMemo)(() => {
        const nonDeleted = query.data?.filter((rule) => !deleted.includes(rule.id)) ?? [];
        const countByType = new Map();
        const filtered = limit > 0
            ? nonDeleted.reduce((carry, rule) => {
                const effectiveSize = Array.from(countByType.keys()).filter((type) => !specialTypes.includes(type)).length;
                const currentCount = countByType.get(rule.typename) ?? 0;
                const isTypeAllowed = specialTypes.includes(rule.typename) ||
                    countByType.has(rule.typename) ||
                    (!countByType.has(rule.typename) && effectiveSize < limit);
                const isFilterAllowed = currentCount < limit;
                if (isTypeAllowed && isFilterAllowed) {
                    countByType.set(rule.typename, (countByType.get(rule.typename) ?? 0) + 1);
                    carry.push(rule);
                }
                return carry;
            }, [])
            : nonDeleted;
        return filtered;
    }, [query.data, deleted, limit]);
    const byType = (0,react.useMemo)(() => {
        return groupBy(rules, "typename");
    }, [rules]);
    const filtersUsageByType = (0,react.useMemo)(() => {
        return new Map(Array.from(byType).map(([type, rules]) => {
            return [type, uniq(rules.map((rule) => rule.filtername))];
        }));
    }, [byType]);
    return {
        byType,
        rules,
        filtersUsageByType,
    };
};

;// ./ui/src/components/Slider.tsx
/* unused harmony import specifier */ var useState;
/* unused harmony import specifier */ var Slider_React;
/* unused harmony import specifier */ var useCallback;
/* unused harmony import specifier */ var Slider_useString;







const slideClasses = classNames("xp-absolute xp-inset-0", !isBehatRunning() ? "xp-transform-gpu xp-transition-transform xp-duration-300" : "");
const slideNextClasses = `${slideClasses} xp-translate-x-full`;
const slidePrevClasses = `${slideClasses} xp--translate-x-full`;
const Slider = ({ children: rawChildren, index }) => {
    const [internalIndex, setInternalIndex] = (0,react.useState)(index);
    const slidesRef = (0,react.useRef)([]);
    const children = react.Children.toArray(rawChildren).filter(Boolean);
    const nSlides = children.length;
    // When the number of slides changes.
    (0,react.useEffect)(() => {
        slidesRef.current = slidesRef.current.slice(0, nSlides);
    }, [nSlides]);
    // Effects when the current slide changes.
    (0,react.useEffect)(() => {
        const Aria = getModule("core/aria");
        slidesRef.current.forEach((item, i) => {
            if (i === internalIndex) {
                Aria.unhide(item);
                item?.focus();
            }
            else {
                Aria.hide(item);
            }
        });
    }, [internalIndex]);
    // When the index changes, update the local one. We do this to let a child render the slide that
    // we should transition to before we update the internal index that would render the child instantly.
    // This allows for the number of slides to be dynamically created by the parent.
    (0,react.useEffect)(() => {
        setInternalIndex(index);
    }, [index]);
    return (react.createElement("div", { className: "xp-w-full xp-h-full xp-overflow-hidden xp-relative" }, react.Children.map(children, (child, i) => {
        const isActive = i === internalIndex;
        const isPast = i < internalIndex;
        return (react.createElement("div", { ref: (el) => (slidesRef.current[i] = el), className: isActive ? slideClasses : isPast ? slidePrevClasses : slideNextClasses }, child));
    })));
};
const SliderTester = () => {
    const [index, setIndex] = useState(0);
    return (Slider_React.createElement("div", null,
        Slider_React.createElement("div", { className: "xp-h-[500px] xp-w-full" },
            Slider_React.createElement(Slider, { index: index },
                Slider_React.createElement(Slide, null,
                    "Slide 1",
                    Slider_React.createElement("div", { className: "xp-w-4", style: { height: "1000px" } })),
                Slider_React.createElement(Slide, null,
                    "Slide 2",
                    Slider_React.createElement("div", { className: "xp-w-4", style: { height: "100px" } })),
                Slider_React.createElement(Slide, null,
                    "Slide 3",
                    Slider_React.createElement("div", { className: "xp-w-4", style: { height: "2000px" } })),
                Slider_React.createElement(Slide, null,
                    "Slide 4",
                    Slider_React.createElement("div", { className: "xp-w-4", style: { height: "500px" } })),
                Slider_React.createElement(Slide, null,
                    "Slide 5",
                    Slider_React.createElement("div", { className: "xp-w-4", style: { height: "1500px" } })))),
        Slider_React.createElement("button", { onClick: () => setIndex((i) => Math.max(0, Math.min(5 - 1, i - 1))) }, "Prev"),
        Slider_React.createElement("button", { onClick: () => setIndex((i) => Math.max(0, Math.min(5 - 1, i + 1))) }, "Next")));
};
const Slide = ({ children, header, footer, }) => {
    /* Firefox requires the vertical scroll to be in the child element, else something odd happens. */
    return (react.createElement("div", { className: "xp-w-full xp-h-full xp-flex xp-flex-col" },
        header,
        react.createElement("div", { className: "xp-flex xp-flex-col xp-grow xp-overflow-y-auto" }, children),
        footer));
};
const SlideHeader = ({ children, title, subtitle, hasBack, onBack, }) => {
    return (react.createElement("div", { className: "xp-mb-2" },
        react.createElement("div", { className: "xp-flex xp-flex-row xp-items-center xp-gap-4" },
            hasBack ? (react.createElement("div", { className: "shrink-0 xp-grow-0" },
                react.createElement(CircleButton, { onClick: onBack, type: "button", className: "xp--mr-2" },
                    react.createElement(ChevronLeftIconSolid, { className: "xp-h-6 xp-w-6" }),
                    react.createElement("span", { className: "xp-sr-only" },
                        react.createElement(components_Str, { id: "back", component: "core" }))))) : null,
            react.createElement("div", { className: "xp-flex-1" },
                subtitle ? react.createElement("div", { className: "xp-text-xs xp-leading-none" }, subtitle) : null,
                react.createElement("div", { className: "xp-text-lg xp-font-bold xp-leading-none" }, title))),
        children));
};
const SlideHeaderWithFilter = ({ hasBack, onBack, onFilterChange, filterValue, filterPlaceholder, title, }) => {
    const filterStr = Slider_useString("filterellipsis");
    const handleChange = useCallback((e) => {
        onFilterChange && onFilterChange(e.currentTarget.value || "");
    }, [onFilterChange]);
    return (Slider_React.createElement(SlideHeader, { hasBack: hasBack, onBack: onBack, title: title },
        Slider_React.createElement("div", { className: "xp-mt-0.5" },
            Slider_React.createElement("input", { className: "form-control xp-w-full", type: "text", value: filterValue || "", placeholder: filterPlaceholder || filterStr, onChange: handleChange }))));
};

;// ./ui/src/components/RuleWizard.tsx










const getUnavailableTypeSettings = (type) => {
    return {
        hasContent: true,
        getContent: () => react.createElement(UnavailableContent, { availabilityInfo: type.availabilityinfo }),
    };
};
const shouldDisplayResource = (availabilityInfo, enablepromo) => {
    const isUnavailable = availabilityInfo?.isavailable === false;
    const isXpPlusRequired = availabilityInfo?.reasons.some((r) => r.code === "xpplusrequired");
    const isXpPremiumRequired = availabilityInfo?.reasons.some((r) => r.code === "xppremiumrequired");
    if (isUnavailable && isXpPlusRequired && !enablepromo && !isXpPremiumRequired) {
        return false;
    }
    return true;
};
const AddRuleModal = ({ onSave, onClose, show, selectedType, }) => {
    const { filters, filtersUsageByType, types } = react.useContext(RulesSetupContext);
    return (react.createElement(RuleWizard, { onSave: onSave, onCancel: onClose, selectedType: selectedType, filters: filters, types: types, filtersUsageByType: filtersUsageByType }, ({ onNext, onCancel, children, nextButtonText, title, canNext }) => {
        const handleSave = (e) => {
            e.preventDefault();
            onNext();
        };
        return (react.createElement(SaveCancelModal, { show: show, large: true, defaultHeight: 500, onSave: handleSave, onClose: onCancel, saveButtonText: nextButtonText, canSave: canNext, title: title }, children));
    }));
};
function RuleWizard({ children, onSave, onCancel, selectedType: preselectedType, autoSelectFilter = false, types, filters, filtersUsageByType, }) {
    const { enablepromo } = (0,react.useContext)(AddonContext);
    const getStr = useStrings(["addanaction", "addacondition", "rulefilteryalreadyusedbyaction"]);
    const getCoreStr = useStrings(["save", "continue"], "core");
    const [index, setIndex] = (0,react.useState)(0);
    const [selectedType, setSelectedType] = (0,react.useState)();
    const [selectedFilter, setSelectedFilter] = (0,react.useState)();
    const [compatibleFilters, setCompatibleFilters] = (0,react.useState)([]);
    const [filterSettings, setFilterSettings] = (0,react.useState)();
    const [typeSettings, setTypeSettings] = (0,react.useState)();
    const [config, setConfig] = (0,react.useState)({ points: 10 });
    const filterIsAutomaticallySelected = compatibleFilters.length === 1 && autoSelectFilter;
    const typesAsResources = (0,react.useMemo)(() => Array.from(types.values())
        .filter((type) => shouldDisplayResource(type.availabilityinfo, enablepromo))
        .sort((a, b) => a.label.localeCompare(b.label))
        .map((type) => ({ ...type, themecolor: getTypeThemeColor(type) })), [types, enablepromo]);
    const hasPreselectedType = Boolean(preselectedType) && types.has(preselectedType);
    const handleSelectedType = (0,react.useCallback)((type) => {
        const typeIsAvailable = type.availabilityinfo?.isavailable ?? true;
        const ruleFilters = Array.from(filters.values())
            .filter((filter) => types.get(type.name)?.filters.includes(filter.name))
            .filter((filter) => shouldDisplayResource(filter.availabilityinfo, enablepromo))
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
            .sort((a, b) => a.label.localeCompare(b.label));
        setCompatibleFilters(ruleFilters);
        setSelectedType(type.name);
        setTypeSettings(!typeIsAvailable ? getUnavailableTypeSettings(type) : undefined);
        const filterToSelect = filterIsAutomaticallySelected ? ruleFilters[0] : null;
        // Code here is mostly a copy of handleSelectedFilter!
        setSelectedFilter(typeIsAvailable && filterToSelect ? filterToSelect.name : undefined);
        setFilterSettings(typeIsAvailable && filterToSelect ? getFilterContentSettings(filterToSelect) : undefined);
        setConfig({ points: 10 });
        setIndex(hasPreselectedType ? 0 : 1);
    }, [filters, hasPreselectedType, types, filtersUsageByType, filterIsAutomaticallySelected, getStr, enablepromo]);
    const handleSelectedFilter = (0,react.useCallback)((filter) => {
        setSelectedFilter(filter.name);
        setFilterSettings(getFilterContentSettings(filter));
        setConfig({ points: 10 });
        setIndex((index) => index + 1);
    }, []);
    const { canClickSaveButton, isStepContinue } = (0,react.useMemo)(() => {
        const hasTypeContent = Boolean(selectedType && typeSettings?.hasContent);
        const hasFilterSelection = !hasTypeContent && !filterIsAutomaticallySelected;
        const hasFilterContent = Boolean(selectedFilter && filterSettings?.hasContent);
        const hasPointsStep = !hasTypeContent && (!selectedFilter || !filterSettings?.hasContent || !filterSettings?.contentIncludesPoints);
        let steps = ["type", "typecontent", "filter", "filtercontent", "points"];
        if (hasPreselectedType) {
            steps = steps.filter((step) => step !== "type");
        }
        if (!hasTypeContent) {
            steps = steps.filter((step) => step !== "typecontent");
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
        const hasRequiredSelection = Boolean(selectedType && (selectedFilter || hasTypeContent));
        const isLastStep = hasRequiredSelection && index === nSlides - 1;
        const isStepContinue = !isLastStep;
        const currentStep = steps[index];
        const isTypeContentStep = currentStep === "typecontent";
        const isFilterContentStep = currentStep === "filtercontent";
        const isPointsStep = currentStep === "points";
        let isStepValid = true;
        if (isTypeContentStep) {
            isStepValid = false;
        }
        else if (isFilterContentStep) {
            isStepValid = filterSettings?.hasContent ? filterSettings.isConfigValid(config) : true;
        }
        else if (isPointsStep) {
            isStepValid = typeof config.points === "number" && !isNaN(config.points) && config.points >= 0;
        }
        const requiresSubmit = Boolean(isFilterContentStep && filterSettings?.hasContent && filterSettings.contentRequiresSubmit);
        const canClickSaveButton = Boolean((isLastStep || requiresSubmit) && isStepValid);
        return {
            canClickSaveButton,
            hasTypeContent,
            hasFilterSelection,
            hasFilterContent,
            hasPointsStep,
            isFirstStep: index === 0,
            isLastStep,
            isStepContinue,
            isStepValid,
            nSlides,
        };
    }, [
        hasPreselectedType,
        config,
        filterSettings,
        typeSettings,
        index,
        selectedFilter,
        selectedType,
        filterIsAutomaticallySelected,
    ]);
    const handleBack = (0,react.useCallback)(() => {
        setIndex((index) => Math.max(0, index - 1));
    }, []);
    const handleContinue = (0,react.useCallback)(() => {
        setIndex((index) => index + 1);
    }, []);
    const handleCancel = () => {
        setIndex(0);
        setSelectedType(undefined);
        setSelectedFilter(undefined);
        setFilterSettings(undefined);
        setTypeSettings(undefined);
        setConfig({ points: 10 });
        onCancel();
    };
    const handleSave = (0,react.useCallback)(() => {
        if (!canClickSaveButton) {
            return;
        }
        if (isStepContinue) {
            setIndex((index) => index + 1);
            return;
        }
        onSave({ ...config, type: selectedType, filter: selectedFilter });
    }, [onSave, selectedType, selectedFilter, isStepContinue, canClickSaveButton, config]);
    const mergeInConfig = (0,react.useCallback)((data) => setConfig((config) => ({ ...config, ...data })), []);
    // Preselect the type.
    (0,react.useEffect)(() => {
        if (!hasPreselectedType || selectedType === preselectedType) {
            return;
        }
        handleSelectedType(types.get(preselectedType));
    }, [preselectedType, selectedType, types, handleSelectedType, hasPreselectedType]);
    const props = {
        canNext: canClickSaveButton,
        nextButtonText: isStepContinue ? getCoreStr("continue") : getCoreStr("save"),
        title: hasPreselectedType ? getStr("addacondition") : getStr("addanaction"),
        onCancel: handleCancel,
        onNext: handleSave,
        children: hasPreselectedType && !selectedType ? null : (react.createElement(Slider, { index: index },
            !hasPreselectedType ? (react.createElement(Slide, null,
                react.createElement(PlainResourceList, { resources: typesAsResources, onSelect: handleSelectedType }))) : null,
            selectedType && typeSettings?.hasContent ? (react.createElement(Slide, { header: react.createElement(SlideHeader, { hasBack: !hasPreselectedType, onBack: handleBack, title: types.get(selectedType)?.label }) }, typeSettings.getContent({
                type: types.get(selectedType ?? ""),
            }))) : null,
            !filterIsAutomaticallySelected ? (react.createElement(Slide, { header: !hasPreselectedType ? (react.createElement(SlideHeader, { hasBack: !hasPreselectedType, onBack: handleBack, title: react.createElement(components_Str, { id: "chooseacondition" }), subtitle: types.get(selectedType ?? "")?.label })) : undefined },
                react.createElement(PlainResourceList, { resources: compatibleFilters, onSelect: handleSelectedFilter }))) : null,
            selectedFilter && filterSettings?.hasContent ? (react.createElement(Slide, { header: react.createElement(SlideHeader, { hasBack: !filterIsAutomaticallySelected || !hasPreselectedType, onBack: handleBack, title: filters.get(selectedFilter)?.label }) }, filterSettings.getContent({
                config,
                setConfig: mergeInConfig,
                onContinue: handleContinue,
                type: types.get(selectedType ?? ""),
            }))) : null,
            selectedFilter && (!filterSettings?.hasContent || !filterSettings?.contentIncludesPoints) ? (react.createElement(Slide, { header: react.createElement(SlideHeader, { hasBack: true, onBack: handleBack, title: filterSettings?.hasContent ? react.createElement(components_Str, { id: "pointstoaward" }) : filters.get(selectedFilter)?.label }) },
                react.createElement(RulePointsLimitsForm, { config: config, setConfig: mergeInConfig, ruleType: types.get(selectedType ?? ""), filterName: selectedFilter ?? "" }))) : null)),
    };
    return children(props);
}

;// ./ui/src/components/Dropdown.tsx




const Dropdown = ({ buttonLabel, items }) => {
    const { activated, enablepromo } = (0,react.useContext)(AddonContext);
    const filteredItems = (0,react.useMemo)(() => {
        return items.filter((item) => {
            if ("addonRequired" in item && item.addonRequired && !activated && !enablepromo) {
                return false;
            }
            return true;
        });
    }, [items, activated, enablepromo]);
    if (filteredItems.length === 0) {
        return null;
    }
    return (react.createElement("div", { className: "dropdown" },
        react.createElement("button", { type: "button", className: "btn btn-link btn-icon icon-size-3 rounded-circle xp-no-underline hover:xp-no-underline", "data-bs-toggle": "dropdown", "data-toggle": "dropdown", "aria-expanded": "false" },
            react.createElement("i", { className: "fa fa-ellipsis-v text-dark py-2", "aria-hidden": "true" }),
            react.createElement("span", { className: "xp-sr-only" }, buttonLabel)),
        react.createElement("div", { className: "dropdown-menu dropdown-menu-right dropdown-menu-end" }, filteredItems.map((item) => {
            if ("divider" in item) {
                return react.createElement("div", { key: item.id, className: "dropdown-divider" });
            }
            return (react.createElement("a", { key: item.id, ...item.props, "aria-disabled": item.disabled ? true : undefined, tabIndex: item.disabled ? -1 : undefined, className: classNames("dropdown-item", item.disabled && "disabled xp-not-italic", item.danger ? "text-danger" : null) },
                react.createElement("div", { className: "xp-flex xp-w-full xp-gap-2" },
                    react.createElement("div", { className: "xp-grow" }, item.label),
                    item.addonRequired ? (react.createElement(IfAddonPromoEnabled, null,
                        react.createElement("div", { className: "xp-flex-0 xp-self-center" },
                            react.createElement(AddonTag, null)))) : null)));
        }))));
};

// EXTERNAL MODULE: ./node_modules/react-animate-height/dist/esm/index.js
var esm = __webpack_require__(6968);
;// ./ui/src/components/Expandable.tsx
/* unused harmony import specifier */ var useRef;
/* unused harmony import specifier */ var Expandable_React;
/* unused harmony import specifier */ var AnimateHeight;
/* unused harmony import specifier */ var Expandable_getModule;



function Expandable({ expanded, children, id }) {
    const ref = useRef(null);
    return (Expandable_React.createElement(AnimateHeight, { id: id, height: expanded ? "auto" : 0, applyInlineTransitions: false, onHeightAnimationStart: () => {
            const Pending = Expandable_getModule("core/pending");
            ref.current?.reject();
            ref.current = Pending ? new Pending("block_xp/expandable") : null;
        }, onHeightAnimationEnd: () => {
            ref.current?.resolve();
        }, animationStateClasses: {
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
        } }, children));
}

;// ./ui/src/components/Tooltip.tsx


const Tooltip = ({ children, content }) => {
    const ref = react.useRef(null);
    (0,react.useEffect)(() => {
        const $ = getModule("jquery");
        if (!$ || !ref.current || !$(ref.current).tooltip) {
            return;
        }
        ref.current.setAttribute("data-container", "body");
        ref.current.setAttribute("title", content);
        $(ref.current).tooltip("enable");
        return () => {
            // There is extra caution here, double checking whether the reference still exists,
            // and is still bound to the tooltip function, and that the tooltip function does
            // not throw an exception. This is to mitigate themes that redeclare Bootstrap and
            // end-up causing troubles.
            if (!ref.current || !$(ref.current).tooltip) {
                return;
            }
            try {
                $(ref.current).tooltip("dispose");
            }
            catch (e) {
                try {
                    $(ref.current).tooltip("destroy");
                }
                catch (e) { }
            }
        };
    }, [content]);
    return (0,react.cloneElement)(children, { ref });
};

;// ./ui/src/components/Rules.tsx
/* unused harmony import specifier */ var Rules_React;
/* unused harmony import specifier */ var useMemo;
/* unused harmony import specifier */ var Rules_useContext;
/* unused harmony import specifier */ var Rules_useUniqueId;
/* unused harmony import specifier */ var Rules_useRuleCreationLimit;
/* unused harmony import specifier */ var Rules_getTypeThemeColor;
/* unused harmony import specifier */ var Rules_classNames;
/* unused harmony import specifier */ var Rules_groupBy;
/* unused harmony import specifier */ var Rules_IfAddonPromoEnabled;
/* unused harmony import specifier */ var Rules_AddonRequiredShort;
/* unused harmony import specifier */ var Rules_ExpandCollapseButton;
/* unused harmony import specifier */ var Rules_Button;
/* unused harmony import specifier */ var Rules_Expandable;
/* unused harmony import specifier */ var Rules_IconRenderer;
/* unused harmony import specifier */ var Rules_Str;
/* unused harmony import specifier */ var Rules_RulesSetupContext;














const RuleTypeIcon = ({ themeColor, icon }) => {
    const [themeFgColor, themeBgColor] = themeColor || [];
    return (Rules_React.createElement("div", { className: Rules_classNames("xp-w-14 xp-h-14 xp-rounded-lg xp-flex xp-text-center xp-items-center xp-justify-center", "xp-text-white xp-bg-indigo-500 xp-text-2xl xp-leading-none"), style: { color: themeFgColor, background: themeBgColor } },
        Rules_React.createElement(Rules_IconRenderer, { icon: icon })));
};
const RuleTypeStatsList = ({ children }) => {
    return Rules_React.createElement("dl", { className: "xp-m-0 xp-flex xp-leading-none xp-gap-4" }, children);
};
const RuleTypeStat = ({ label, value }) => {
    return (Rules_React.createElement("div", { className: "xp-flex-1 xp-shrink-0" },
        Rules_React.createElement("dt", { className: "xp-m-0 xp-font-normal xp-uppercase xp-text-2xs xp-text-gray-500" }, label),
        Rules_React.createElement("dd", { className: "xp-m-0 xp-font-normal xp-text xp-text-sm xp-whitespace-nowrap xp-truncate" }, value)));
};
const RuleTypeGroup = ({ type, rules, filters, expanded, onExpanded, }) => {
    const expandableId = Rules_useUniqueId();
    const filterLimit = Rules_useRuleCreationLimit();
    const { isEmpty, pointsMin, pointsMax, nRules, rulesByFilter } = useMemo(() => {
        const pointsList = rules.map((r) => r.points);
        const pointsMin = Math.min(...pointsList);
        const pointsMax = Math.max(...pointsList);
        return {
            isEmpty: rules.length === 0,
            pointsMin,
            pointsMax,
            nRules: rules.length,
            rulesByFilter: Array.from(Rules_groupBy(rules, "filtername")),
        };
    }, [rules]);
    const { addRule, editRule, removeRule, viewRuleLogs } = Rules_useContext(Rules_RulesSetupContext);
    const hasReachedFilterLimit = useMemo(() => {
        return filterLimit > 0 && rules.length >= filterLimit;
    }, [filterLimit, rules]);
    return (Rules_React.createElement("div", { className: "xp-rounded-lg xp-border xp-border-solid xp-border-gray-200 xp-p-3", id: `xp-ruletype-${type.name}` },
        Rules_React.createElement("div", { className: "xp-flex xp-items-center xp-gap-2" },
            Rules_React.createElement("div", { className: "xp-grow xp-flex xp-gap-3" },
                type.icon ? (Rules_React.createElement("div", { className: "xp-shrink-0 xp-grow-0" },
                    Rules_React.createElement(RuleTypeIcon, { themeColor: Rules_getTypeThemeColor(type), icon: type.icon }))) : null,
                Rules_React.createElement("div", { className: "xp-grow" },
                    Rules_React.createElement("h4", { className: "xp-text-lg xp-leading-none xp-m-0" }, type.label),
                    Rules_React.createElement("div", { className: "xp-mt-2" },
                        Rules_React.createElement(RuleTypeStatsList, null,
                            Rules_React.createElement(RuleTypeStat, { label: Rules_React.createElement(Rules_Str, { id: "points" }), value: isEmpty ? "-" : pointsMin === pointsMax ? pointsMin : `${pointsMin} – ${pointsMax}` }),
                            Rules_React.createElement(RuleTypeStat, { label: Rules_React.createElement(Rules_Str, { id: "conditions" }), value: nRules }))))),
            Rules_React.createElement("div", { className: "xp-grow-0 xp-shrink-0" },
                Rules_React.createElement(Rules_ExpandCollapseButton, { ariaControlsId: expandableId, expanded: expanded, onToggle: onExpanded }))),
        Rules_React.createElement(Rules_Expandable, { expanded: expanded, id: expandableId },
            isEmpty ? (Rules_React.createElement("div", { className: "xp-pt-3" },
                Rules_React.createElement("div", { className: "xp-rounded xp-border-dashed xp-border-2 xp-p-4 xp-py-6 xp-text-center xp-border-gray-200" },
                    Rules_React.createElement("div", { className: "xp-text-xl xp-font-bold xp-mb-4" },
                        Rules_React.createElement(Rules_Str, { id: "noconditionsyet" })),
                    Rules_React.createElement("div", null,
                        Rules_React.createElement(Rules_Str, { id: "noconditionsyetintro" })),
                    Rules_React.createElement("div", { className: "xp-mt-4" },
                        Rules_React.createElement("button", { className: "btn btn-primary", onClick: () => addRule(type.name) },
                            Rules_React.createElement(Rules_Str, { id: "addcondition" })))))) : null,
            !isEmpty ? (Rules_React.createElement("div", { className: "xp-pt-2" },
                Rules_React.createElement("div", { className: "xp-flex xp-my-2" },
                    Rules_React.createElement("div", { className: "xp-grow" }),
                    Rules_React.createElement("div", { className: "xp-shrink-0 xp-flex xp-gap-2 xp-items-center" },
                        Rules_React.createElement(Rules_IfAddonPromoEnabled, null, hasReachedFilterLimit ? (Rules_React.createElement(Rules_React.Fragment, null,
                            Rules_React.createElement("div", { className: "xp-text-sm xp-text-gray-700 xp-leading-none" },
                                Rules_React.createElement(Rules_Str, { id: "upgradetoaddmore" })),
                            Rules_React.createElement(Rules_AddonRequiredShort, null))) : null),
                        Rules_React.createElement(Rules_Button, { className: "btn-sm", primary: true, outline: true, onClick: () => addRule(type.name), disabled: hasReachedFilterLimit },
                            Rules_React.createElement(Rules_Str, { id: "addcondition" })))),
                Rules_React.createElement(RulesSectionGroup, { enclosed: true }, rulesByFilter.map(([filterName, rules]) => {
                    const filter = filters.get(filterName);
                    if (!filter)
                        return null;
                    return (Rules_React.createElement(RulesSection, { key: filterName, title: filter.label, description: filter.description }, rules.map((rule) => {
                        return (Rules_React.createElement(RuleEntry, { key: rule.id, rule: rule, type: type, onDelete: () => removeRule(rule.id), onEdit: () => editRule(rule.id), onViewLogs: viewRuleLogs ? () => viewRuleLogs(rule.id) : undefined }));
                    })));
                })))) : null)));
};
const RulesSectionGroup = ({ children, enclosed }) => {
    return (react.createElement("div", { className: classNames("xp-space-y-4", 
        /* When the group is enclosed (it has borders around it) we remove the border of the very last entry. */
        enclosed ? "[&_.xp-rules-section:last-child_.xp-rule-entry:last-child]:xp-border-0" : null) }, children));
};
const RulesSection = ({ children, title, description, }) => {
    return (react.createElement("div", { className: "xp-rules-section" },
        react.createElement("h5", { className: "xp-font-bold xp-m-0 xp-mb-1 xp-text-base" }, title),
        react.createElement("p", { className: "xp-mb-2 xp-text-sm xp-text-gray-500 xp-m-0" }, description),
        react.createElement("div", { className: "[&>div]:xp-border-0 [&>div]:xp-border-b [&>div]:xp-border-solid [&>div]:xp-border-gray-200" }, children)));
};
const RuleEntry = ({ rule, type, onDelete, onEdit, onViewLogs, extra, }) => {
    const { points, label } = rule;
    return (react.createElement("div", { className: "xp-rule-entry" },
        react.createElement("div", { className: "xp-flex xp-gap-2 xp-py-1" },
            react.createElement("div", { className: "xp-shrink-0" },
                react.createElement("div", { className: classNames("xp-min-w-[86px] xp-min-h-7 xp-flex xp-items-center xp-text-center xp-justify-center xp-rounded", "xp-px-2 xp-py-0.5 xp-font-bold xp-tracking-wide", !points ? "xp-bg-gray-200" : "xp-bg-blue-100") }, points !== null ? `${points != 0 ? "+" : ""}${points}` : "-")),
            react.createElement("div", { className: "xp-flex xp-grow xp-items-center" },
                react.createElement("div", { className: "xp-grow xp-flex xp-justify-end xp-items-center xp-flex-wrap" },
                    react.createElement("div", { className: "xp-grow" }, label),
                    extra ? react.createElement("div", { className: "xp-shrink-0" }, extra) : null),
                type ? (react.createElement("div", { className: "xp-flex-0 xp-flex xp-items-center" },
                    react.createElement(RuleLimit, { rule: rule, type: type }))) : null),
            react.createElement("div", { className: "xp-shrink-0 xp--my-1 xp-flex xp-items-center" },
                react.createElement(RuleDropdown, { onDelete: onDelete, onEdit: onEdit, onViewLogs: onViewLogs })))));
};
const LimitPerWindow = ({ max, window }) => {
    const getStr = useStrings(["nperhoursmall", "nperdaysmall", "nperweeksmall", "npermonthsmall"]);
    const key = (0,react.useMemo)(() => {
        switch (window) {
            case LimitSpecTimeWindow.ONEHOUR:
            case LimitSpecTimeWindow.HOUR:
                return "nperhoursmall";
            case LimitSpecTimeWindow.DAILY:
                return "nperdaysmall";
            case LimitSpecTimeWindow.WEEKLY:
                return "nperweeksmall";
            case LimitSpecTimeWindow.MONTHLY:
                return "npermonthsmall";
        }
        return;
    }, [window]);
    if (!key) {
        return react.createElement(react.Fragment, null, max);
    }
    return react.createElement(react.Fragment, null, getStr(key, max));
};
const RuleLimit = ({ rule, type }) => {
    const limit = (0,react.useMemo)(() => {
        if ("limit" in rule && rule.limit) {
            return rule.limit;
        }
        return type.defaultlimit;
    }, [rule, type]);
    const repeatlimit = (0,react.useMemo)(() => {
        if ("repeatlimit" in rule && rule.repeatlimit) {
            return rule.repeatlimit;
        }
        return type.defaultrepeatlimit;
    }, [rule, type]);
    if (!rule.points || !ruleTypeSupportsLimits(type)) {
        return null;
    }
    return react.createElement(RuleLimitContent, { limit: limit, repeatlimit: repeatlimit });
};
const RuleLimitContent = ({ limit, repeatlimit }) => {
    const getStr = useStrings(["repetitionlimitset"]);
    if (!limit?.max && !repeatlimit?.max) {
        return null;
    }
    return (react.createElement("div", { className: "xp-text-xs xp-text-gray-500 xp-leading-none xp-whitespace-nowrap xp-flex xp-items-center xp-gap-1" },
        limit?.max ? (react.createElement("span", null,
            "Max: ",
            react.createElement(LimitPerWindow, { max: limit.max, window: limit.timewindow }))) : null,
        repeatlimit?.max ? (react.createElement(Tooltip, { content: getStr("repetitionlimitset") },
            react.createElement("span", null,
                react.createElement(IconRenderer, { icon: { type: "fa", value: "history" } })))) : null));
};
const noop = () => { };
const RuleDropdown = ({ onEdit, onDelete, onViewLogs, }) => {
    const isAddonEnabled = useAddonActivated();
    const deleteProps = useAnchorButtonProps(onDelete);
    const viewLogsProps = useAnchorButtonProps(onViewLogs ?? noop);
    const editProps = useAnchorButtonProps(onEdit);
    return (react.createElement(Dropdown, { buttonLabel: react.createElement(components_Str, { id: "options", component: "core" }), items: [
            { id: "edit", label: react.createElement(components_Str, { id: "edit", component: "core" }), props: editProps },
            ...(onViewLogs
                ? [
                    {
                        id: "logs",
                        label: react.createElement(components_Str, { id: "viewlogs", component: "block_xp" }),
                        props: viewLogsProps,
                        addonRequired: !isAddonEnabled,
                        disabled: !isAddonEnabled,
                    },
                ]
                : []),
            { id: "divider", divider: true },
            { id: "delete", label: react.createElement(components_Str, { id: "delete", component: "core" }), props: deleteProps, danger: true },
        ] }));
};

;// ./ui/src/components/ZeroStates.tsx

const ZeroState = ({ title, intro, children, }) => {
    return (react.createElement("div", { className: "xp-rounded xp-border-dashed xp-border-2 xp-p-4 xp-py-6 xp-text-center xp-border-gray-200" },
        react.createElement("div", { className: "xp-text-xl xp-font-bold xp-mb-4" }, title),
        react.createElement("div", null, intro),
        children ? react.createElement("div", { className: "xp-mt-4" }, children) : null));
};

// EXTERNAL MODULE: ./node_modules/@tanstack/query-core/build/lib/queryClient.mjs + 4 modules
var queryClient = __webpack_require__(4968);
// EXTERNAL MODULE: ./node_modules/@tanstack/react-query/build/lib/useMutation.mjs + 1 modules
var useMutation = __webpack_require__(1154);
;// ./ui/src/lib/query.ts





const query_queryClient = new queryClient/* QueryClient */.E({
    defaultOptions: {
        queries: {
            staleTime: 1000 * 60,
            onError: (err) => getModule("core/notification").exception(err),
        },
        mutations: {
            onError: (err) => getModule("core/notification").exception(err),
        },
    },
});
const useAddRuleMutation = (contextid, childcontextid, { types }, { onSuccess }) => {
    const addonActivated = useAddonActivated();
    return (0,useMutation/* useMutation */.n)(async ({ type, filter, ...config }) => {
        const ruleid = await ajaxRequest("block_xp_create_rule", {
            contextid,
            childcontextid: childcontextid ?? 0,
            points: config.points ?? 0,
            type: {
                name: type,
                char1: config.typechar1 ?? null,
            },
            filter: {
                name: filter,
                courseid: config.filtercourseid ?? null,
                cmid: config.filtercmid ?? null,
                int1: config.filterint1 ?? null,
                char1: config.filterchar1 ?? null,
            },
        });
        const ruleType = types.get(type);
        if (addonActivated && !config.usedefaultlimits && ruleType && ruleTypeSupportsLimits(ruleType)) {
            const initialLimits = getInitialLimitFieldsFromRuleType(ruleType);
            await ajaxRequest("local_xp_set_rule_limits", {
                ruleid,
                limits: {
                    limitmax: config.limitmax ?? initialLimits.limitmax,
                    limitwindow: config.limitwindow ?? initialLimits.limitwindow,
                    repeatscope: config.repeatscope ?? initialLimits.repeatscope,
                    repeatwindow: config.repeatwindow ?? initialLimits.repeatwindow,
                },
            });
        }
        return ruleid;
    }, {
        onSuccess,
    });
};
const useDeleteRuleMutation = () => {
    return (0,useMutation/* useMutation */.n)(async ({ id }) => {
        return ajaxRequest("block_xp_delete_rule", { id });
    });
};
const useRules = (kind, contextid, childcontextid) => {
    const queryClient = (0,QueryClientProvider/* useQueryClient */.jE)();
    const queryKey = (0,react.useMemo)(() => [`${kind}-rules`, contextid, childcontextid], [kind, contextid, childcontextid]);
    const data = (0,useQuery/* useQuery */.I)({
        queryKey,
        queryFn: async () => {
            return await ajaxRequest("block_xp_get_rules", {
                kind,
                contextid,
                childcontextid,
            });
        },
    });
    const invalidateQuery = (0,react.useCallback)(() => {
        queryClient.invalidateQueries({ queryKey });
    }, [queryClient, queryKey]);
    return {
        ...data,
        invalidateQuery,
    };
};
const invalidateRuleTypeLimitsQuery = (contextid, childcontextid) => {
    query_queryClient.invalidateQueries({ queryKey: ["ruletype-limits", contextid, childcontextid] });
};

;// ./ui/src/completion-rules.tsx

















const availableRuleTypes = ["cm_completion", "section_completion", "course_completion"];
const guessMethodFromLocation = () => {
    return Math.max(0, availableRuleTypes.indexOf((window.location.hash ?? "").replace(/^#/, "")));
};
const updateLocationFromMethodIndex = (idx) => {
    const hash = "#" + availableRuleTypes[idx];
    window.location.hash = hash;
};
const App = (props) => {
    const world = (0,react.useContext)(WorldContext);
    const { navigateTo } = world;
    const currentContext = props.childcontext ?? world.context;
    const rulesContextData = useMakeRulesSetupContext(props);
    const [selectedTabIndex, setSelectedTabIndex] = (0,react.useState)(guessMethodFromLocation);
    const [optimisticallyDeleted, setOptimisticallyDeleted] = (0,react.useState)([]);
    const childcontextid = props.childcontext?.id ?? null;
    const [isAdding, setIsAdding] = (0,react.useState)(false);
    const [isEditing, setIsEditing] = (0,react.useState)(null);
    const [isDeleting, setIsDeleting] = (0,react.useState)(null);
    const getStr = useStrings(["deletecondition", "editcondition", "ruleadded"]);
    const rulesQuery = useRules("completion", world.context.id, childcontextid);
    const { invalidateQuery } = rulesQuery;
    const { byType: rules, filtersUsageByType } = useRulesInfo(rulesQuery, optimisticallyDeleted);
    const addRuleMutation = useAddRuleMutation(world.context.id, childcontextid, rulesContextData, {
        onSuccess: () => {
            invalidateQuery();
            setIsAdding(false);
            const Toast = getModule("core/toast");
            Toast && Toast.add(getStr("ruleadded"));
        },
    });
    const deleteRuleMutation = useDeleteRuleMutation();
    const handleSelectedTabIndexChange = (idx) => {
        setSelectedTabIndex(idx);
        setIsAdding(false);
        setIsEditing(null);
        setIsDeleting(null);
        updateLocationFromMethodIndex(idx);
    };
    const handleViewRuleLogs = (0,react.useCallback)((ruleId) => navigateTo("log", { ruleid: ruleId }), [navigateTo]);
    const currentRuleType = availableRuleTypes[selectedTabIndex];
    const typesByName = rulesContextData.types;
    const canAdd = true;
    const showAddBtnInTabs = canAdd && rules.get(currentRuleType)?.length;
    if (rulesQuery.isLoading || rulesQuery.isError) {
        return react.createElement(AppLoading, null);
    }
    return (react.createElement(RulesSetupContext.Provider, { value: {
            addRule: () => setIsAdding(true),
            editRule: (ruleId) => setIsEditing(ruleId),
            removeRule: (ruleId) => setIsDeleting(ruleId),
            viewRuleLogs: handleViewRuleLogs,
            context: currentContext,
            filters: rulesContextData.filters,
            filtersUsageByType: filtersUsageByType,
            types: rulesContextData.types,
        } },
        react.createElement("div", null,
            react.createElement(tabs/* Tab */.o.Group, { selectedIndex: selectedTabIndex, onChange: handleSelectedTabIndexChange },
                react.createElement(tabs/* Tab */.o.List, { as: "div", className: "nav nav-tabs" },
                    react.createElement(tabs/* Tab */.o, { as: react.Fragment }, ({ selected }) => (react.createElement("button", { className: classNames("nav-item nav-link", selected ? "active" : null) },
                        react.createElement(components_Str, { id: "activity", component: "core" })))),
                    react.createElement(tabs/* Tab */.o, { as: react.Fragment }, ({ selected }) => (react.createElement("button", { className: classNames("nav-item nav-link", selected ? "active" : null) },
                        react.createElement(components_Str, { id: "section", component: "core" })))),
                    react.createElement(tabs/* Tab */.o, { as: react.Fragment }, ({ selected }) => (react.createElement("button", { className: classNames("nav-item nav-link", selected ? "active" : null) },
                        react.createElement(components_Str, { id: "course", component: "core" })))),
                    react.createElement("div", { className: "xp-flex-1 xp-flex xp-justify-end xp-items-center" }, showAddBtnInTabs ? (react.createElement("button", { className: "btn btn-primary btn-sm", onClick: () => setIsAdding(true) },
                        react.createElement(components_Str, { id: "add", component: "core" }))) : null)),
                react.createElement(tabs/* Tab */.o.Panels, { className: "xp-mt-4" },
                    react.createElement(tabs/* Tab */.o.Panel, null, typesByName.has("cm_completion") ? (react.createElement(CompletionRules, { rules: rules.get("cm_completion") ?? [], type: typesByName.get("cm_completion") })) : (react.createElement(NotificationError, null,
                        react.createElement(components_Str, { id: "unknowntypea", a: "cm_completion" })))),
                    react.createElement(tabs/* Tab */.o.Panel, null, typesByName.has("section_completion") ? (react.createElement(CompletionRules, { rules: rules.get("section_completion") ?? [], type: typesByName.get("section_completion") })) : (react.createElement(NotificationError, null,
                        react.createElement(components_Str, { id: "unknowntype", a: "section_completion" })))),
                    react.createElement(tabs/* Tab */.o.Panel, null, typesByName.has("course_completion") ? (react.createElement(CompletionRules, { rules: rules.get("course_completion") ?? [], type: typesByName.get("course_completion") })) : (react.createElement(NotificationError, null,
                        react.createElement(components_Str, { id: "unknowntype", a: "course_completion" }))))))),
        react.createElement(AddRuleModal, { show: isAdding, selectedType: currentRuleType, onClose: () => setIsAdding(false), onSave: ({ filter, ...config }) => {
                addRuleMutation.mutate({ type: currentRuleType, filter, ...config });
            } }),
        react.createElement(DeleteModal, { show: isDeleting !== null, onClose: () => setIsDeleting(null), onDelete: () => {
                if (!isDeleting)
                    return;
                setOptimisticallyDeleted([...optimisticallyDeleted, isDeleting]);
                deleteRuleMutation.mutate({ id: isDeleting }, {
                    onError: () => {
                        setOptimisticallyDeleted(optimisticallyDeleted.filter((id) => id !== isDeleting));
                    },
                    onSuccess: () => {
                        invalidateQuery();
                    },
                    onSettled: () => {
                        setIsDeleting(null);
                    },
                });
            }, title: getStr("deletecondition") },
            react.createElement(components_Str, { id: "areyousure", component: "core" })),
        isEditing ? (react.createElement(ModalForm, { formClass: props.ruleformclass, formArgs: { id: isEditing }, title: getStr("editcondition"), onClose: () => setIsEditing(null), onSubmit: () => {
                setIsEditing(null);
                invalidateQuery();
            } })) : null));
};
const NoRulesZeroState = ({ onClick }) => {
    return (react.createElement(ZeroState, { title: react.createElement(components_Str, { id: "noconditionsyet" }), intro: react.createElement(components_Str, { id: "noconditionsyetintro" }) },
        react.createElement("button", { className: "btn btn-primary", onClick: onClick },
            react.createElement(components_Str, { id: "add", component: "core" }))));
};
const CompletionRules = ({ rules: rulesV2, type }) => {
    const { addRule, removeRule, editRule, filters, viewRuleLogs } = (0,react.useContext)(RulesSetupContext);
    const rules = (0,react.useMemo)(() => {
        return (rulesV2?.map((rule) => ({
            ...rule,
            filter: rule.filtername,
            method: rule.typename,
        })) ?? []);
    }, [rulesV2]);
    const filteredRules = (0,react.useMemo)(() => rules?.filter((r) => type.filters.includes(r.filter) && filters.has(r.filter)), [rules, type.filters, filters]);
    const groupedRules = (0,react.useMemo)(() => Array.from(groupBy(filteredRules, "filter")), [filteredRules]);
    const handleAddClick = () => {
        addRule();
    };
    if (!filteredRules?.length) {
        return react.createElement(NoRulesZeroState, { onClick: handleAddClick });
    }
    return (react.createElement(RulesSectionGroup, null, groupedRules.map(([filter, rules]) => {
        const ruleFilter = filters.get(filter);
        if (!ruleFilter)
            return null;
        return (react.createElement(RulesSection, { key: filter, title: ruleFilter?.label, description: ruleFilter?.description }, rules.map((rule) => {
            return (react.createElement(RuleEntry, { key: rule.id, rule: rule, onDelete: () => removeRule(rule.id), onEdit: () => editRule(rule.id), onViewLogs: viewRuleLogs ? () => viewRuleLogs(rule.id) : undefined }));
        })));
    })));
};
function startApp(node, props) {
    const root = (0,client/* createRoot */.H)(node);
    root.render(react.createElement(WorldContext.Provider, { value: makeWorldContextValueFromAppProps(props) },
        react.createElement(AddonContext.Provider, { value: makeAddonContextValueFromAppProps(props) },
            react.createElement(QueryClientProvider/* QueryClientProvider */.Ht, { client: query_queryClient },
                react.createElement(App, { ...props })))));
}
const dependencies = makeDependenciesDefinition(commonStaticModulesToDependOn);



/***/ }

/******/ 	});
/************************************************************************/
/******/ 	// The module cache
/******/ 	var __webpack_module_cache__ = {};
/******/ 	
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/ 		// Check if module is in cache
/******/ 		var cachedModule = __webpack_module_cache__[moduleId];
/******/ 		if (cachedModule !== undefined) {
/******/ 			return cachedModule.exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = __webpack_module_cache__[moduleId] = {
/******/ 			// no module.id needed
/******/ 			// no module.loaded needed
/******/ 			exports: {}
/******/ 		};
/******/ 	
/******/ 		// Execute the module function
/******/ 		__webpack_modules__[moduleId](module, module.exports, __webpack_require__);
/******/ 	
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/ 	
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = __webpack_modules__;
/******/ 	
/************************************************************************/
/******/ 	/* webpack/runtime/chunk loaded */
/******/ 	(() => {
/******/ 		var deferred = [];
/******/ 		__webpack_require__.O = (result, chunkIds, fn, priority) => {
/******/ 			if(chunkIds) {
/******/ 				priority = priority || 0;
/******/ 				for(var i = deferred.length; i > 0 && deferred[i - 1][2] > priority; i--) deferred[i] = deferred[i - 1];
/******/ 				deferred[i] = [chunkIds, fn, priority];
/******/ 				return;
/******/ 			}
/******/ 			var notFulfilled = Infinity;
/******/ 			for (var i = 0; i < deferred.length; i++) {
/******/ 				var [chunkIds, fn, priority] = deferred[i];
/******/ 				var fulfilled = true;
/******/ 				for (var j = 0; j < chunkIds.length; j++) {
/******/ 					if ((priority & 1 === 0 || notFulfilled >= priority) && Object.keys(__webpack_require__.O).every((key) => (__webpack_require__.O[key](chunkIds[j])))) {
/******/ 						chunkIds.splice(j--, 1);
/******/ 					} else {
/******/ 						fulfilled = false;
/******/ 						if(priority < notFulfilled) notFulfilled = priority;
/******/ 					}
/******/ 				}
/******/ 				if(fulfilled) {
/******/ 					deferred.splice(i--, 1)
/******/ 					var r = fn();
/******/ 					if (r !== undefined) result = r;
/******/ 				}
/******/ 			}
/******/ 			return result;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/create fake namespace object */
/******/ 	(() => {
/******/ 		var getProto = Object.getPrototypeOf ? (obj) => (Object.getPrototypeOf(obj)) : (obj) => (obj.__proto__);
/******/ 		var leafPrototypes;
/******/ 		// create a fake namespace object
/******/ 		// mode & 1: value is a module id, require it
/******/ 		// mode & 2: merge all properties of value into the ns
/******/ 		// mode & 4: return value when already ns object
/******/ 		// mode & 16: return value when it's Promise-like
/******/ 		// mode & 8|1: behave like require
/******/ 		__webpack_require__.t = function(value, mode) {
/******/ 			if(mode & 1) value = this(value);
/******/ 			if(mode & 8) return value;
/******/ 			if(typeof value === 'object' && value) {
/******/ 				if((mode & 4) && value.__esModule) return value;
/******/ 				if((mode & 16) && typeof value.then === 'function') return value;
/******/ 			}
/******/ 			var ns = Object.create(null);
/******/ 			__webpack_require__.r(ns);
/******/ 			var def = {};
/******/ 			leafPrototypes = leafPrototypes || [null, getProto({}), getProto([]), getProto(getProto)];
/******/ 			for(var current = mode & 2 && value; (typeof current == 'object' || typeof current == 'function') && !~leafPrototypes.indexOf(current); current = getProto(current)) {
/******/ 				Object.getOwnPropertyNames(current).forEach((key) => (def[key] = () => (value[key])));
/******/ 			}
/******/ 			def['default'] = () => (value);
/******/ 			__webpack_require__.d(ns, def);
/******/ 			return ns;
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/define property getters */
/******/ 	(() => {
/******/ 		// define getter functions for harmony exports
/******/ 		__webpack_require__.d = (exports, definition) => {
/******/ 			for(var key in definition) {
/******/ 				if(__webpack_require__.o(definition, key) && !__webpack_require__.o(exports, key)) {
/******/ 					Object.defineProperty(exports, key, { enumerable: true, get: definition[key] });
/******/ 				}
/******/ 			}
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/hasOwnProperty shorthand */
/******/ 	(() => {
/******/ 		__webpack_require__.o = (obj, prop) => (Object.prototype.hasOwnProperty.call(obj, prop))
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/make namespace object */
/******/ 	(() => {
/******/ 		// define __esModule on exports
/******/ 		__webpack_require__.r = (exports) => {
/******/ 			if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 				Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 			}
/******/ 			Object.defineProperty(exports, '__esModule', { value: true });
/******/ 		};
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/runtimeId */
/******/ 	(() => {
/******/ 		__webpack_require__.j = 834;
/******/ 	})();
/******/ 	
/******/ 	/* webpack/runtime/jsonp chunk loading */
/******/ 	(() => {
/******/ 		// no baseURI
/******/ 		
/******/ 		// object to store loaded and loading chunks
/******/ 		// undefined = chunk not loaded, null = chunk preloaded/prefetched
/******/ 		// [resolve, reject, Promise] = chunk loading, 0 = chunk loaded
/******/ 		var installedChunks = {
/******/ 			834: 0
/******/ 		};
/******/ 		
/******/ 		// no chunk on demand loading
/******/ 		
/******/ 		// no prefetching
/******/ 		
/******/ 		// no preloaded
/******/ 		
/******/ 		// no HMR
/******/ 		
/******/ 		// no HMR manifest
/******/ 		
/******/ 		__webpack_require__.O.j = (chunkId) => (installedChunks[chunkId] === 0);
/******/ 		
/******/ 		// install a JSONP callback for chunk loading
/******/ 		var webpackJsonpCallback = (parentChunkLoadingFunction, data) => {
/******/ 			var [chunkIds, moreModules, runtime] = data;
/******/ 			// add "moreModules" to the modules object,
/******/ 			// then flag all "chunkIds" as loaded and fire callback
/******/ 			var moduleId, chunkId, i = 0;
/******/ 			if(chunkIds.some((id) => (installedChunks[id] !== 0))) {
/******/ 				for(moduleId in moreModules) {
/******/ 					if(__webpack_require__.o(moreModules, moduleId)) {
/******/ 						__webpack_require__.m[moduleId] = moreModules[moduleId];
/******/ 					}
/******/ 				}
/******/ 				if(runtime) var result = runtime(__webpack_require__);
/******/ 			}
/******/ 			if(parentChunkLoadingFunction) parentChunkLoadingFunction(data);
/******/ 			for(;i < chunkIds.length; i++) {
/******/ 				chunkId = chunkIds[i];
/******/ 				if(__webpack_require__.o(installedChunks, chunkId) && installedChunks[chunkId]) {
/******/ 					installedChunks[chunkId][0]();
/******/ 				}
/******/ 				installedChunks[chunkId] = 0;
/******/ 			}
/******/ 			return __webpack_require__.O(result);
/******/ 		}
/******/ 		
/******/ 		var chunkLoadingGlobal = globalThis["webpackChunkblock_xp"] = globalThis["webpackChunkblock_xp"] || [];
/******/ 		chunkLoadingGlobal.forEach(webpackJsonpCallback.bind(null, 0));
/******/ 		chunkLoadingGlobal.push = webpackJsonpCallback.bind(null, chunkLoadingGlobal.push.bind(chunkLoadingGlobal));
/******/ 	})();
/******/ 	
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module depends on other loaded chunks and execution need to be delayed
/******/ 	var __webpack_exports__ = __webpack_require__.O(undefined, [224], () => (__webpack_require__(8917)))
/******/ 	__webpack_exports__ = __webpack_require__.O(__webpack_exports__);
/******/ 	
/******/ 	return __webpack_exports__;
/******/ })()
;
});;