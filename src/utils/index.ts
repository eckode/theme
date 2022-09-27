/**
 * Remove trailing slash from  string, normally a  url/path
 *
 * @param {string} str String to remove trailing slash from.
 * @returns string
 */
export function unTrailingSlashIt<Type extends string>(str: Type): string {
  return str.endsWith("/") ? str.slice(0, -1) : str;
}

export default {
  unTrailingSlashIt,
};
