import React, { useCallback, useEffect, useRef, useState } from "react";
import * as ReactDOM from "react-dom";
import { useDuplicatedActionPreventor, useModules, useString } from "../lib/hooks";
import type { CoreModal, CoreModalFormInstance } from "../lib/types";

function getModalButton(modal: any, action: string): HTMLButtonElement | null {
  if (!modal) return null;
  const btnJq = modal.getFooter().find(modal.getActionSelector(action));
  return btnJq.length ? btnJq[0] : null;
}

function getModalFormButton(modalForm: CoreModalFormInstance, action: string): HTMLButtonElement | null {
  return getModalButton(modalForm?.modal, action);
}

export const SaveCancelModal = ({
  children,
  onClose,
  onSave,
  show,
  title,
  saveButtonText,
  defaultHeight,
  large,
  canSave = true,
}: {
  children: React.ReactNode;
  onClose?: () => void;
  onSave?: (e: Event) => void;
  show?: boolean;
  canSave?: boolean;
  title?: string;
  large?: boolean;
  saveButtonText?: string;
  defaultHeight?: number;
}) => {
  const modalPromise = useRef<Promise<CoreModal>>();
  const modalRef = useRef<CoreModal>();
  // In rare instances, we can get double save events. This can happen when we hit enter,
  // and a new event listener is registered while Moodle is still broadcasting its events
  // which is then called, and so we get two events. This wouldn't happen if the modal was
  // not re-rendering, I think.
  const isSavePermitted = useDuplicatedActionPreventor();
  const { getModule } = useModules(["block_xp/modal", "core/modal_events"] as const);
  const [ready, setReady] = useState(false);

  const setSaveButtonText = (text?: string) => {
    const saveBtn = getModalButton(modalRef.current, "save");
    if (!saveBtn || !text) return;
    saveBtn.textContent = text;
  };

  const setButtonAttribute = (attr: string, value: any) => {
    const saveBtn = getModalButton(modalRef.current, "save");
    if (!saveBtn || !attr) return;
    if (value === null || typeof value === "undefined" || value === false) {
      saveBtn.removeAttribute(attr);
    } else {
      saveBtn.setAttribute(attr, value);
    }
  };

  // Create the modal object.
  useEffect(() => {
    let cancelled = false;
    if (modalRef.current) return;

    const Modal = getModule("block_xp/modal");
    if (!Modal) return;

    if (!modalPromise.current) {
      modalPromise.current = Modal.createSaveCancelModal({
        title: title,
        large: large,
        body: `<div class='block_xp' style='${defaultHeight ? `height: ${defaultHeight}px` : ""}'></div>`,
      }) as Promise<CoreModal>;
    }

    modalPromise.current
      .then((modal) => {
        if (cancelled) return;

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
  useEffect(() => {
    const modal = modalRef.current;
    if (!modal) return;

    const ModalEvents = getModule("core/modal_events");
    if (!ModalEvents) return;

    const root = modal.getRoot();

    const handleSave = (e: Event) => {
      if (!isSavePermitted()) return;
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
      const reactNode = body ? body.querySelector<HTMLDivElement>(".block_xp") : null;
      if (!body || !reactNode) {
        return;
      }
      const height =
        body.clientHeight - (parseFloat(getComputedStyle(body).paddingTop) + parseFloat(getComputedStyle(body).paddingBottom));
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
  useEffect(() => {
    if (!modalRef.current) return;
    if (show) {
      modalRef.current.show();
    } else {
      modalRef.current.hide();
    }
  }, [show, modalRef.current]); // eslint-disable-line react-hooks/exhaustive-deps

  // Update title.
  useEffect(() => {
    if (!modalRef.current || !title) return;
    modalRef.current.setTitle(title);
  }, [title, modalRef.current]); // eslint-disable-line react-hooks/exhaustive-deps

  // Update save button text.
  useEffect(() => {
    setSaveButtonText(saveButtonText);
  }, [saveButtonText, modalRef.current]); // eslint-disable-line react-hooks/exhaustive-deps

  // Update the save button status.
  useEffect(() => {
    setButtonAttribute("disabled", !canSave);
  }, [canSave, modalRef.current]); // eslint-disable-line react-hooks/exhaustive-deps

  return (
    <>
      {modalRef.current
        ? ReactDOM.createPortal(
            children,
            modalRef.current.getBody()[0].querySelector<HTMLDivElement>(".block_xp") as HTMLDivElement,
          )
        : null}
    </>
  );
};

export const DeleteModal = ({
  children,
  onClose,
  onDelete,
  show,
  title,
}: {
  children: React.ReactNode;
  onClose?: () => void;
  onDelete?: (e: Event) => void;
  show?: boolean;
  title?: string;
}) => {
  const modalPromise = useRef<Promise<CoreModal>>();
  const modalRef = useRef<CoreModal>();
  const [ready, setReady] = useState(false);
  const isDeletePermitted = useDuplicatedActionPreventor();
  const deleteStr = useString("delete", "core");
  const { getModule } = useModules(["block_xp/modal", "core/modal_events"] as const);

  // Create the modal object.
  useEffect(() => {
    let cancelled = false;
    if (modalRef.current) return;

    const Modal = getModule("block_xp/modal");
    if (!Modal) return;

    if (!modalPromise.current) {
      modalPromise.current = Modal.createSaveCancelModal({
        title: title,
        body: `<div class='block_xp'></div>`,
      }) as Promise<CoreModal>;
    }

    modalPromise.current
      .then((modal) => {
        if (cancelled) return;

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
  useEffect(() => {
    const modal = modalRef.current;
    if (!modal) return;

    const ModalEvents = getModule("core/modal_events");
    if (!ModalEvents) return;

    const root = modal.getRoot();

    const handleSave = (e: Event) => {
      if (!isDeletePermitted()) return;
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
  useEffect(() => {
    if (!modalRef.current) return;
    if (show) {
      modalRef.current.show();
    } else {
      modalRef.current.hide();
    }
  }, [show, modalRef.current]); // eslint-disable-line react-hooks/exhaustive-deps

  // Update title.
  useEffect(() => {
    if (!modalRef.current || !title) return;
    modalRef.current.setTitle(title);
  }, [title, modalRef.current]); // eslint-disable-line react-hooks/exhaustive-deps

  // Update button.
  useEffect(() => {
    if (!modalRef.current || !deleteStr) return;
    const btn = getModalButton(modalRef.current, "save");
    if (!btn) return;
    btn.textContent = deleteStr;
  }, [deleteStr, modalRef.current]); // eslint-disable-line react-hooks/exhaustive-deps

  return (
    <>
      {modalRef.current
        ? ReactDOM.createPortal(children, modalRef.current.getBody()[0].querySelector(".block_xp") as HTMLDivElement)
        : null}
    </>
  );
};

export const ModalForm = ({
  formClass,
  formArgs,
  onClose,
  onSubmit,
  saveButtonDisabled,
  title,
}: {
  formClass: string;
  formArgs?: Record<string, any>;
  onClose?: () => void;
  onSubmit?: () => void;
  saveButtonDisabled?: boolean;
  title?: string;
}) => {
  const modalFormRef = useRef<CoreModalFormInstance | null>();
  const { getModule } = useModules(["core_form/modalform", "core/modal_events"] as const);

  // Create the modal form.
  useEffect(() => {
    if (modalFormRef.current) return;

    const ModalForm = getModule("core_form/modalform");
    if (!ModalForm) return;

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
  useEffect(() => {
    const modalForm = modalFormRef.current;
    if (!modalForm) return;

    const ModalForm = getModule("core_form/modalform");
    const ModalEvents = getModule("core/modal_events");
    if (!ModalForm || !ModalEvents) return;

    const handleLoaded = () => {
      const root = modalForm.modal!.getRoot();
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
      if (!modalForm) return;
      const root = modalForm.modal!.getRoot();
      const rootEl = root?.[0];
      rootEl?.removeEventListener(modalForm.events.LOADED, handleLoaded);
      rootEl?.removeEventListener(modalForm.events.FORM_SUBMITTED, handleSubmit);
      rootEl?.removeEventListener(modalForm.events.CANCEL_BUTTON_PRESSED, handleClose);
      root.off(ModalEvents.hidden, handleClose);
    };
  });

  useEffect(() => {
    if (!modalFormRef.current) return;
    const modal = modalFormRef.current.modal;
    if (!modal) return;
    modal.setTitle(title ?? "");
  }, [title]);

  return null;
};
