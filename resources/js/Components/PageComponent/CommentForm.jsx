import { useForm, usePage } from "@inertiajs/react";
import PrimaryButton from "../PrimaryButton";
import InputError from "../InputError";
import { Transition } from "@headlessui/react";
import { Button, Dialog, DialogBody, DialogFooter, DialogHeader, Textarea } from "@material-tailwind/react";
import { useCallback, useEffect, useState } from "react";
import Urls from "@/network/Urls";
import LoginForm from "../Custom/LoginForm";

const CommentForm = (params) => {
	const [addSuccess, setAddSuccess] = useState(false);
	const [open, setOpen] = useState(false);
	const handleOpen = () => setOpen(!open);
	const { component, props: { auth: { user } }, scrollRegions, rememberedState, url
	} = usePage();

	const { data, setData, errors, reset, setError } = useForm({ // hook error when login then back.
		name: user?.name,
		email: user?.email,
		user_id: user?.id,
		target_id: params.pageId,
		content: '',
		parent_id: params?.parent_id,
	});

	const submit = useCallback((e) => {
		e.preventDefault();
		/**
		 * controller auto detech user context of the request. 
		 */
		axios.post(Urls.addComment, {
			...data, ...{
				name: user?.name,
				email: user?.email,
				user_id: user?.id,
			}
		}).then(function (response) {
			setAddSuccess(true);
			reset();
		}).catch(function (error) {
			console.log('?????', error.response.data.message);
		});
	}, [reset, data, user?.name, user?.id, user?.email]);

	useEffect(() => {
		if (addSuccess) {
			setTimeout(() => {
				setAddSuccess(false);
			}, 3000)
		}
	}, [addSuccess])

	if (user) {
		return (
			<div className="bg-white rounded-md p-2">
				<p className="font-bold text-xl underline my-1">Bình luận của bạn:</p>
				<CommentUser></CommentUser>
				{addSuccess && <span className="font-semibold text-lg text-red-500">add success new comment</span>}
				<form action="" onSubmit={submit} className="mt-2 space-y-2">
					<div className="space-y-2">
						<Textarea
							variant="outlined"
							placeholder="comment content"
							color={'black'}
							value={data.content}
							className="text-lg font-bold rounded-md"
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

	return (
		<div className="bg-white rounded-md p-2">
			<p className="font-bold text-xl underline my-1" onClick={handleOpen}>Thêm bình luận:</p>
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
			</Dialog>
		</div>
	);
}

const CommentUser = () => {
	const { props: { auth: { user } }, version, } = usePage();
	return (
		<div className="flex gap-x-3">
			<img src={user.profile_photo_path || user?.profile_photo_url} alt="" className="object-cover rounded-md w-[64px] h-[64px]" />
			<div>
				<span className="text-xl font-semibold">user name: {user?.name}</span>
				<h3 className="text-xl font-bold">email: {user?.email}</h3>
			</div>

		</div>
	)
}

export default CommentForm;