import React from 'react';
import { useStrings } from '../lib/hooks';
import Pix from './Pix';
import Spinner from './Spinner';
import Str from './Str';

export const SaveButton: React.FC<{
  mutation?: any;
  disabled?: boolean;
  onClick?: () => void;
  label?: string;
  statePosition?: 'before' | 'after';
}> = ({ onClick, disabled, label, mutation = {}, statePosition = 'after' }) => {
  const getStr = useStrings(['changessaved', 'error'], 'core');
  const { isLoading, isSuccess, isError } = mutation;
  const isStateBefore = statePosition === 'before';

  const state = (
    <div className={`xp-w-8 xp-flex ${isStateBefore ? 'xp-mr-4 xp-justify-end' : 'xp-ml-4'}`} aria-live="assertive">
      {isLoading ? <Spinner /> : null}
      {isSuccess ? <Pix id="i/valid" component="core" alt={getStr('changessaved')} /> : null}
      {isError ? <Pix id="i/invalid" component="core" alt={getStr('error')} /> : null}
    </div>
  );

  return (
    <div className="xp-flex xp-items-center">
      {isStateBefore ? state : null}
      <div className="">
        <button className="btn btn-primary" onClick={onClick} disabled={disabled || isLoading} type="button">
          {label || <Str id="savechanges" component="core" />}
        </button>
      </div>
      {!isStateBefore ? state : null}
    </div>
  );
};
