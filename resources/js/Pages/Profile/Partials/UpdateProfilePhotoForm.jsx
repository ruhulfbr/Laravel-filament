import {useRef} from 'react';
import PrimaryButton from '@/Components/PrimaryButton';
import {useForm, usePage} from '@inertiajs/react';
import {Transition} from '@headlessui/react';

export default function UpdateProfilePhotoForm({user}) {

    return (
        // <div className="flex flex-col items-center">
        //     <img
        //         src="https://randomuser.me/api/portraits/men/94.jpg"
        //         className="w-32 h-32 bg-gray-300 rounded-full mb-4 shrink-0"
        //         alt="Profile Photo"
        //     />
        //     <h1 className="text-xl font-bold">{user.name}</h1>
        //     <p className="text-gray-700">{user.email}</p>
        // </div>

        <div className="flex flex-col items-center">
            <div className="relative">
                <img
                    src="https://randomuser.me/api/portraits/men/94.jpg"
                    className="w-32 h-32 bg-gray-300 rounded-full mb-4 shrink-0"
                    alt="Profile Photo"
                />
                <button className="absolute top-1 right-1 bg-gray-800 hover:bg-gray-900 text-white p-1 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" className="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path
                            d="M17.414 2.586a2 2 0 010 2.828l-9.192 9.192a2 2 0 01-.707.414l-3 1a1 1 0 01-1.265-1.265l1-3a2 2 0 01.414-.707l9.192-9.192a2 2 0 012.828 0zM15.707 3.293l-9.192 9.192-1 3 3-1 9.192-9.192a1 1 0 10-1.414-1.414z"/>
                    </svg>
                </button>
            </div>
            <h1 className="text-xl font-bold">{user.name}</h1>
            <p className="text-gray-700">{user.email}</p>
        </div>


    );
}
