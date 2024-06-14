import React, {useState} from 'react';

const Alert = ({type, message}) => {
    const [isVisible, setIsVisible] = useState(true);

    if (!isVisible) return null;

    let styles = {
        base: "flex items-center justify-between p-4 mb-4 text-sm border rounded-lg",
        icon: "flex-shrink-0 inline w-4 h-4 mr-3",
        closeBtn: "ml-auto -mx-1.5 -my-1.5 text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 inline-flex h-8 w-8",
        closeBtnBg: "",
        text: "",
        border: "",
        bg: ""
    };

    switch (type) {
        case 'info':
            styles = {
                ...styles,
                text: "text-blue-800",
                closeBtnText: "text-blue-500",
                border: "border-blue-300",
                bg: "bg-blue-50"
            };
            break;
        case 'danger':
            styles = {
                ...styles,
                text: "text-red-800",
                closeBtnText: "text-red-500",
                border: "border-red-300",
                bg: "bg-red-50"
            };
            break;
        case 'success':
            styles = {
                ...styles,
                text: "text-green-800",
                closeBtnText: "text-green-500",
                border: "border-green-300",
                bg: "bg-green-50"
            };
            break;
        case 'warning':
            styles = {
                ...styles,
                text: "text-yellow-800",
                closeBtnText: "text-yellow-500",
                border: "border-yellow-300",
                bg: "bg-yellow-50"
            };
            break;
        case 'dark':
            styles = {
                ...styles,
                text: "text-gray-800",
                closeBtnText: "text-gray-500",
                border: "border-gray-300",
                bg: "bg-gray-50"
            };
            break;
        default:
            break;
    }

    return (
        <div className={`${styles.base} ${styles.text} ${styles.border} ${styles.bg}`} role="alert">
            <div className="flex items-center">
                <svg
                    className={styles.icon}
                    aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                >
                    <path
                        d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
                </svg>
                <div>
                    <span className="font-medium">{type.charAt(0).toUpperCase() + type.slice(1)} alert!</span> {message}
                </div>
            </div>
            <button
                type="button"
                className={`${styles.closeBtn} ${styles.closeBtnText}`}
                aria-label="Close"
                onClick={() => setIsVisible(false)}
            >
                <span className="sr-only">Close</span>
                <svg
                    aria-hidden="true"
                    className="w-5 h-5"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        fillRule="evenodd"
                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                        clipRule="evenodd"
                    />
                </svg>
            </button>
        </div>
    );
};

export default Alert;
