export const classNames = (...args: any[]) => args.filter(Boolean).join(" ");

const escapeCharMap: Record<string, string> = {
  '&': '&amp;',
  '<': '&lt;',
  '>': '&gt;',
  '"': '&quot;',
  "'": '&#039;'
};
export const escapeHtml = (text: string) => {
  return text.replace(/[&<>"']/g, function(m) { return escapeCharMap[m]; });
}

export const fifoCache = <T>(maxItems = 128): { get: (k: string) => T | undefined; set: (k: string, v: T) => void } => {
  let items: { [index: string]: any } = {};
  let keys: string[] = [];

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
    set: (key: string, value: any) => {
      items[key] = value;
      keys.push(key);
      purge();
    },
    get: (key: string) => {
      return items[key];
    },
  };
};

let uniqueId = 0;
export const getUniqueId = () => {
  return `xp-${Date.now()}-${uniqueId++}`;
};

export const groupBy = <T, K extends keyof T = keyof T>(arr: T[], key: K): Map<string, T[]> => {
  const map = new Map<string, T[]>();
  for (const entry of arr) {
    const index = entry[key] as string;
    if (!map.has(index)) {
      map.set(index, [] as any);
    }
    map.set(index, (map.get(index)??[]as any).concat(entry));
  }
  return map;
}

export const mapFrom = <T, K extends keyof T>(arr: T[], key: K): Map<string, T> => {
  return new Map<string, T>(arr.map(entry => ([entry[key] as string, entry])));
}

export const stripTags = (html: string) => {
  var tmp = document.createElement("div");
  tmp.innerHTML = html;
  return tmp.textContent || tmp.innerText || "";
};

export const uniq = (arr: any[]) => {
  return arr.filter((value, index, self) => self.indexOf(value) === index);
};