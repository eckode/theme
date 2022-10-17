import * as Content from "../components/content/types";
import { ContentModel, DbId } from "../types";

const { boot } = window.Eckode;

export const addStaticPayload = (initialState: Content.State) => {
  const { context, context_value: contextValue } = boot;
  switch (context) {
    case "single":
      initialState.all[boot.id] = boot;
      break;
    case "home":
      initialState.types.post = [
        boot.props.post?.reduce(
          (acc: Array<number>, { id }: { id: DbId }) => {
            acc.push(id);
            return acc;
          },
          [],
        ) ?? [],
      ];
      initialState.all = {
        ...initialState.all,
        ...(boot.props.post?.reduce(
          (acc: Content.AllObject, content: ContentModel) => {
            acc[content.id] = content;
            return acc;
          },
          {},
        ) ?? {}),
      };
      break;
    case "taxonomy_archive":
    case "post_type_archive":
      initialState.types[contextValue] = [
        boot.props[contextValue]?.reduce(
          (acc: Array<number>, { id }: { id: DbId }) => {
            acc.push(id);
            return acc;
          },
          [],
        ) ?? [],
      ];
      initialState.all = {
        ...initialState.all,
        ...(boot.props[contextValue]?.reduce(
          (acc: Content.AllObject, content: ContentModel) => {
            acc[content.id] = content;
            return acc;
          },
          {},
        ) ?? {}),
      };
      break;
  }

  return initialState;
};
