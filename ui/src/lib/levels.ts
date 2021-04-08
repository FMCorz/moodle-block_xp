import { Level } from './types';

export function computeRequiredPoints(level: number, base: number, coef: number) {
  if (level <= 1) {
    return 0;
  } else if (level == 2) {
    return base;
  }

  if (coef <= 1) {
    return base * (level - 1);
  }

  return Math.round(base * ((1 - Math.pow(coef, level - 1)) / (1 - coef)));
}

export const getLevel = (levels: Level[], level: number): Level | undefined => {
  return levels[Math.max(0, level - 1)];
};

export const getMinimumPointsForLevel = (levels: Level[], level: Level) => {
  if (level.level === 1 || !levels.length) {
    return 0;
  }
  return getPreviousLevel(levels, level).xprequired + 1;
};

export const getMinimumPointsAtLevel = (levels: Level[], level: number) => {
  const l = getLevel(levels, level - 1);
  return l ? l.xprequired + 1 : 0;
};

export const getNextLevel = (levels: Level[], level: Level, highest: number = 9999): Level | undefined => {
  let index = Math.min(highest, Math.max(levels.indexOf(level) + 1, 0));
  return levels[index];
};

export const getPreviousLevel = (levels: Level[], level: Level) => {
  return levels[Math.max(levels.indexOf(level) - 1, 0)];
};
