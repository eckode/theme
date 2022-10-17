/**
 * Remove trailing slash from  string, normally a  url/path
 *
 * @param {string} str String to remove trailing slash from.
 * @returns string
 */
 export function unTrailingSlashIt<T extends string>(str: T): string {
  return str.endsWith("/") && str.length > 1 ? str.slice(0, -1) : str;
}

/**
 * Remove leading slash from  string, normally a url/path
 *
 * @param {string} str String to remove trailing slash from.
 * @returns string
 */
 export function unLeadingSlashIt<T extends string>(str: T): string {
  return str.startsWith("/") && str.length > 1 ? str.slice(1, -1) : str;
}

export default {
  unTrailingSlashIt,
  unLeadingSlashIt,
};
