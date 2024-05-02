import React, { InputHTMLAttributes, SelectHTMLAttributes, TextareaHTMLAttributes } from "react";

const Input = ({ className = "", ...props }: InputHTMLAttributes<HTMLInputElement>) => {
  /** Apply those classes for normalised styling across themes and versions. */
  return <input {...props} className={`xp-m-0 form-control ${className}`} />;
};

export const Select = ({ className = "", ...props }: SelectHTMLAttributes<HTMLSelectElement>) => {
  /** Apply those classes for normalised styling across themes and versions. */
  return <select {...props} className={`xp-m-0 xp-max-w-auto form-select form-control ${className}`} />;
};

export const Textarea = ({ className = "", ...props }: TextareaHTMLAttributes<HTMLTextAreaElement>) => {
  /** Apply those classes for normalised styling across themes and versions. */
  return <textarea {...props} className={`xp-m-0 form-control ${className}`} />;
};

export const FieldHelp = ({ children }: { children: React.ReactNode }) => {
  return <p className="xp-text-gray-500 xp-m-0 xp-mt-1">{children}</p>;
};

export default Input;
