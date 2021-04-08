import React from 'react';
import { useString } from '../lib/hooks';

import { Level } from '../lib/types';

const Level: React.FC<{ level: Level; small?: boolean }> = ({ level, small }) => {
  const label = useString('levelx', 'block_xp', level.level);
  const classes = 'block_xp-level level-' + level.level + (small ? ' small' : '');

  if (level.badgeurl) {
    return (
      <div className={classes + ' level-badge'} aria-label={label}>
        <img src={level.badgeurl} alt={label} />
      </div>
    );
  }

  return (
    <div className={classes} aria-label={label}>
      {level.level}
    </div>
  );
};

export default Level;
