
import { useCallback, useEffect, useState } from "react";
import { useForm, usePage } from "@inertiajs/react";
import { Transition } from "@headlessui/react";
import { Textarea } from "@material-tailwind/react";
import { ArrowTurnDownRightIcon, PlusCircleIcon, ArrowTurnUpLeftIcon, ArrowUpLeftIcon, ArrowUpIcon,} from "@heroicons/react/24/solid";
import InputError from "../InputError";
import PrimaryButton from "../PrimaryButton";
import { useListComment } from "@/hook/useComments";
import Urls from "@/network/Urls";
import CommentInfo from "./CommentInfo";

const CommentItem = ({ comment, replyId, onReply }) => {
	const [showReply, setShowReply] = useState(false);
	const { props: { auth: {user} } } = usePage();
	const { data: listReply, isFetching, isFetchingNextPage, hasNextPage, fetchNextPage } = useListComment({
		type: 'page',
		targetId: comment.target_id,
		enabled: showReply,
		parent_id: comment.id,
	});

	return (
		<div>
			<div className="px-1 flex space-x-1 w-full">
				<div>
					<img src={comment.user.profile_photo_path || comment.user.profile_photo_url} alt="" className="rounded-md w-[64px] h-[64px]" />
				</div>
				<div className="flex-1">
					<div className="md:text-lg text-blue-400">
						<p className="md:text-xl font-bold text-orange-400 underline inline-block">{comment.user.name}:</p>&nbsp;{comment.content}
					</div>
					<div className="flex space-x-2 justify-between">
						{!!comment.children_count &&
							<div className="flex self-baseline">
								{showReply ? <ArrowUpIcon onClick={() => setShowReply(false)} className="h-5 w-5"></ArrowUpIcon> : <>
									<ArrowTurnDownRightIcon color="" className="h-5 w-5"></ArrowTurnDownRightIcon>
									<span>&nbsp;</span>
									<span onClick={() => setShowReply(true)}>show {comment.children_count} more for reply</span>
								</>}
							</div>
						}
						<div className="flex space-x-3">
							<span className="font-bold italic underline" onClick={() => onReply(replyId === comment.id ? 0 : comment.id)}>
								{(replyId === comment.id) && user ?
									<ArrowUpLeftIcon color="red" className="h-5 w-5"></ArrowUpLeftIcon> :
									<ArrowTurnUpLeftIcon color="red" className="h-5 w-5"></ArrowTurnUpLeftIcon>
								}
							</span>
							<CommentInfo comment={comment}></CommentInfo>
						</div>
					</div>

				</div>
			</div>
			{(replyId === comment.id) && user && <ReplyForm comment={comment} onSuccess={() => { onReply(0) }}></ReplyForm>}
			{
				showReply && listReply && <div className="pl-2 md:pl-5 space-y-1 mt-1">
					{listReply.map((reply, index) => {
						return <CommentItem comment={reply} key={index.toString()} replyId={replyId} onReply={onReply}></CommentItem>
					})}
					{hasNextPage && <div className="flex justify-center items-center" onClick={fetchNextPage}>
						<PlusCircleIcon width={40} height={40}></PlusCircleIcon>
					</div>}
				</div>
			}
		</div>
	)
}

const ReplyForm = ({ comment, onSuccess }) => {
	const [addSuccess, setAddSuccess] = useState(false);
	const { props: { auth: { user } } } = usePage();
	const { data, setData, errors, reset, setError } = useForm({
		name: user?.name,
		email: user?.email,
		target_id: comment.target_id,
		content: '',
		user_id: user?.id,
		parent_id: comment.id,
	});

	const submit = useCallback((e) => {
		e.preventDefault();
		axios.post(Urls.addComment, data)
			.then(function (response) {
				reset();
				setAddSuccess(true);
			})
			.catch(function (error) {
				console.log('?????', error.response.data.message);
			});
	}, [reset, data]);

	useEffect(() => {
		if (addSuccess) {
			setTimeout(() => {
				setAddSuccess(false);
				onSuccess();
			}, 3000)
		}
	}, [addSuccess, onSuccess])

	return (
		<div>
			{addSuccess && <span className="font-semibold text-lg text-green-500">add success new comment</span>}
			<form action="" onSubmit={submit} className="mt-1 space-y-2">
				<div className="space-y-2">
					<Textarea
						variant="outlined"
						label="comment content"
						color={'black'}
						value={data.content}
						className="text-lg font-bold"
						style={{ fontSize: 18, fontWeight: 'bold', }}
						onChange={(e) => setData('content', e.target.value)}
					/>
					<InputError className="mt-2" message={errors.content} />
				</div>

				<div className="flex items-center justify-end gap-4">
					<PrimaryButton disabled={false}>Save</PrimaryButton>
					<Transition
						show={false}
						enter="transition ease-in-out"
						enterFrom="opacity-0"
						leave="transition ease-in-out"
						leaveTo="opacity-0"
					>
						<p className="text-sm text-gray-600">Saved.</p>
					</Transition>
				</div>
			</form>
		</div>
	)
}

export default CommentItem;