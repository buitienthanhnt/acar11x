import { getCommentList, getTopComment } from "@/query/comments"
import { useInfiniteQuery, useQuery } from "@tanstack/react-query"
import { useCallback, useMemo, useState } from "react"

// https://tanstack.com/query/v4/docs/framework/react/reference/useInfiniteQuery
// https://tanstack.com/query/latest/docs/framework/react/reference/infiniteQueryOptions
const useListComment = ({ type, targetId, enabled, parent_id, }) => {
	const {
		data,
		error,
		fetchNextPage,
		hasNextPage,
		isFetching,
		isFetchingNextPage,
		status,
	} = useInfiniteQuery({
		queryKey: ['comment', type, targetId, parent_id],
		queryFn: ({ pageParam = 1 }) => getCommentList(targetId, parent_id, pageParam),
		initialPageParam: 1,
		getNextPageParam: (lastPage, pages,) => {
			if (lastPage.current_page < lastPage.last_page) {
				return lastPage.current_page + 1
			}
			return undefined;
		},
		retry: false,
		enabled: enabled,
		staleTime: 0, //cache time
		refetchOnWindowFocus: false,
		refetchOnMount: true,
	})

	return {
		data: data ? data.pages.map(item => item.data).flat() : null,
		error,
		fetchNextPage,
		hasNextPage, isFetching, isFetchingNextPage, status
	}
}

const commentInfoKey = 'comment-info';

const useCommentInfo = (comment) => {
	const [key, setKey] = useState('');

	const added = useMemo(() => {
		const commentLike = localStorage.getItem(commentInfoKey + '-like')?.split("|") || [];
		const commentHeart = localStorage.getItem(commentInfoKey + '-heart')?.split("|") || [];
		const commentfire = localStorage.getItem(commentInfoKey + '-fire')?.split("|") || [];
		const commentStar = localStorage.getItem(commentInfoKey + '-star')?.split("|") || [];
		return {
			like: commentLike,
			heart: commentHeart,
			star: commentStar,
			fire: commentfire,
		}
	}, [key])

	const onAdd = useCallback((type) => {
		const isAdded = added[type].includes(comment.id.toString());

		axios.post('/add-source', {
			target_id: comment.id,
			action: !isAdded ? 'add' : 'sub',
			action_type: type,
			type: 'comment',
		}).then(
			function ({ data }) {
				if (isAdded) {
					localStorage.setItem(commentInfoKey + '-' + type, added[type].filter(i => i !== comment.id.toString()).join('|'));
				} else {
					localStorage.setItem(commentInfoKey + '-' + type, [comment.id.toString(), ...added[type]].join('|'));
				}
				setKey(type + (isAdded ? '_add_' : '_remove_') + comment.id.toString());
			}
		).catch(function (error) {
			console.log('Error:=============>', error);
		})
	}, [added])

	return {
		...added,
		onAdd,
	}
}

const useTopComment = () => {
	const query = useQuery({
		queryKey: ['top-comment'],
		queryFn: getTopComment,
	});

	return { ...query };
}

export { useListComment, useCommentInfo, useTopComment }