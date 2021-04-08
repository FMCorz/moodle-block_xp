import React from 'react';
import NumericInput, { NumericInputProps } from 'react-numeric-input';

const sizes: { [index: number]: string } = {
  4: 'xp-w-20',
};

const NumberInput = ({ className = '', ...props }: NumericInputProps) => {
  const width = props.size ? sizes[props.size] || '' : '';
  return (
    <div className={`xp-inline-block ${width}`}>
      <NumericInput {...props} className={`form-control xp-m-0 xp-h-full ${className}`} />
    </div>
  );
};

export default NumberInput;
