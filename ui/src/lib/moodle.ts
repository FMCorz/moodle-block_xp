import { fifoCache } from './utils';

const M = (window as any).M;
const modules: { [index: string]: any } = {};

export function getString(id: string, component: string, a?: any) {
  return M.util.get_string(id, component, a);
}

export function getUrl(uri: string) {
  if (uri[0] != '/') {
    uri = '/' + uri;
  }
  return M.cfg.wwwroot + uri;
}

export function hasString(id: string, component: string) {
  return typeof M.str[component] !== 'undefined' && typeof M.str[component][id] !== 'undefined';
}

export function getModule(name: string): any {
  return modules[name];
}

export function imageUrl(name: string, component: string) {
  return M.util.image_url(name, component);
}

export function isBehatRunning() {
  return M.cfg.behatsiterunning;
}

let loadStringCache = fifoCache<Promise<any>>(64);

export async function loadString(id: string, component: string) {
  const cacheKey = `${id}/${component}`;
  let promise = loadStringCache.get(cacheKey);
  if (!promise) {
    promise = getModule('core/str').get_string(id, component);
    loadStringCache.set(cacheKey, promise as Promise<any>);
  }
  return await promise;
}

export async function loadStrings(ids: string[], component: string) {
  const cacheKey = `${ids.join(',')}/${component}`;
  let promise = loadStringCache.get(cacheKey);
  if (!promise) {
    promise = getModule('core/str').get_strings(ids.map((id) => ({ key: id, component })));
    loadStringCache.set(cacheKey, promise as Promise<any>);
  }
  return await promise;
}

export const makeDependenciesDefinition = (names: string[]) => {
  return {
    list: names,
    loader: (mods: any[]) => {
      mods.forEach((mod, i) => {
        setModule(names[i], mod);
      });
    },
  };
};

export function setModule(name: string, mod: any) {
  modules[name] = mod;
}
