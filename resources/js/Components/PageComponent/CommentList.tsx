import { createContext, useCallback, useState } from "react";
import { usePage } from "@inertiajs/react";
import { Button, Dialog, DialogBody, DialogFooter, DialogHeader, Spinner } from "@material-tailwind/react";
import { PlusCircleIcon } from "@heroicons/react/24/solid";
import { useListComment } from "@/hook/useComments";
import LoginForm from "../Custom/LoginForm";
import CommentItem from "./CommentItem";

const CommentContext = createContext();

const CommentList = () => {
	const { props: { page: { id }, auth: { user } } } = usePage();
	const [open, setOpen] = useState(false);
	const handleOpen = () => setOpen(!open);
	const [replyId, setReplyId] = useState(0);
	const { data, isFetching, isFetchingNextPage, hasNextPage, fetchNextPage } = useListComment({
		type: 'page',
		targetId: id,
		enabled: true,
	});

	const onReply = useCallback((commentId) => {
		setReplyId(commentId);
		if (!user) {
			handleOpen();
		}
	}, [user])

	if (!data?.length) {
		return null;
	}

	if (isFetching || isFetchingNextPage) {
		return <div className="bg-white p-1 lg:px-2 rounded-md justify-center items-center flex">
			<Spinner width={40} height={40}></Spinner>
		</div>
	}

	return (
		<>
			<div className="bg-white p-1 lg:px-2 rounded-md">
				<p className="font-bold text-xl underline my-1">Danh sách bình luận:</p>
				<div className="space-y-1">
					{data.map((item, index) => <CommentItem comment={item} key={index.toString()} replyId={replyId} onReply={onReply} ></CommentItem>)}
				</div>
				{hasNextPage && <div className="flex justify-center items-center" onClick={fetchNextPage}>
					<PlusCircleIcon width={40} height={40}></PlusCircleIcon>
				</div>}
			</div>

			<Dialog open={open} handler={handleOpen}>
				<DialogHeader>Please login for comment!</DialogHeader>
				<DialogBody>
					<LoginForm onSuccess={handleOpen}></LoginForm>
				</DialogBody>
				<DialogFooter>
					<Button
						variant="text"
						color="red"
						onClick={handleOpen}
						className="mr-1"
					>
						<span>Cancel</span>
					</Button>
				</DialogFooter>
			</Dialog></>
	)
}

export { CommentList, };