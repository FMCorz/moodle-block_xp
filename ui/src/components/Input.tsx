import React from 'react';

const Input: React.FC<React.InputHTMLAttributes<HTMLInputElement>> = ({ className = '', ...props }) => {
  /** Apply those classes for normalised styling across themes and versions. */
  return <input {...props} className={`xp-m-0 xp-h-auto form-control ${className}`} />;
};

export default Input;
