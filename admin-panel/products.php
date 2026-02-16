def receive_receipt(update: Update, context: CallbackContext):
    user_id = update.message.from_user.id

    if user_id not in USER_STATE:
        return

    if USER_STATE[user_id]["state"] != "waiting_receipt":
        return

    pid = USER_STATE[user_id]["product_id"]
    product = PRODUCTS[pid]

    # ارسال پیام به ادمین
    context.bot.send_message(
        chat_id=ADMIN_ID,
        text=
        f"🧾 رسید جدید\n\n"
        f"👤 کاربر: {update.message.from_user.full_name}\n"
        f"🆔 ID: {user_id}\n"
        f"📦 محصول: {product['name']}\n"
        f"💰 مبلغ: {product['price']}"
    )

    # فوروارد خود رسید
    context.bot.forward_message(
        chat_id=ADMIN_ID,
        from_chat_id=update.message.chat_id,
        message_id=update.message.message_id
    )

    update.message.reply_text(
        "✅ رسید دریافت شد\n"
        "پس از بررسی، سرویس برای شما فعال می‌شود 🙏"
    )

    del USER_STATE[user_id]
