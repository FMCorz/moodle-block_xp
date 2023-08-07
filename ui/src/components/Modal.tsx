import { useEffect, useRef, useState } from "react";
import * as ReactDOM from "react-dom";
import { getModule } from "../lib/moodle";

export const SaveCancelModal: React.FC<{
  onClose?: () => void;
  onSave?: () => void;
  show?: boolean;
  title?: string;
  saveButtonText?: string;
}> = ({ children, onClose, onSave, show, title, saveButtonText }) => {
  const modalPromise = useRef<Promise<any>>();
  const modalRef = useRef<any>();
  const [ready, setReady] = useState(false);

  const setSaveButtonText = (text?: string) => {
    if (!modalRef.current || !text) return;
    const saveBtn = modalRef.current.getFooter()[0].querySelector('[data-action="save"]');
    if (!saveBtn) return;
    saveBtn.textContent = saveButtonText;
  };

  // Create the modal object.
  useEffect(() => {
    let cancelled = false;
    if (modalRef.current) return;

    if (!modalPromise.current) {
      const ModalFactory = getModule("core/modal_factory");
      modalPromise.current = ModalFactory.create({
        type: ModalFactory.types.SAVE_CANCEL,
        title: title,
        body: "<div class='block_xp'></div>",
      }) as Promise<any>;
    }

    modalPromise.current.then((modal) => {
      if (cancelled) return;

      modalRef.current = modal;

      if (saveButtonText) {
        setSaveButtonText(saveButtonText);
      }
      setReady(true); // State update to force re-render.

      if (show) {
        modal.show();
      }
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
    const root = modal.getRoot();

    const handleSave = () => {
      onSave && onSave();
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
  }, [show, modalRef.current]);

  // Update title.
  useEffect(() => {
    if (!modalRef.current || !title) return;
    modalRef.current.setTitle(title);
  }, [title, modalRef.current]);

  // Update save button text.
  useEffect(() => {
    setSaveButtonText(saveButtonText);
  }, [saveButtonText, modalRef.current]);

  return modalRef.current ? ReactDOM.createPortal(children, modalRef.current.getBody()[0].querySelector(".block_xp")) : null;
};
