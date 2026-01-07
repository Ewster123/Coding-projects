from py_tale import Py_Tale
import asyncio, json

bot = Py_Tale()

bot.config(client_id = 'client_e64332a4-4069-4ff3-8965-d58e20d6cc1d',
            user_id = 1727824970,
            scope_string = 'ws.group ws.group_members ws.group_servers ws.group_bans ws.group_invites group.info group.join group.leave group.view group.members group.invite server.view server.console',
            client_secret = '###############',
            debug = True)


async def main():
    asyncio.create_task(bot.run())                  # Run the bot
    await bot.wait_for_ws()                         # This waits until the main websocket is ready
    invites = await bot.request_invites()           # Get all of our invites to servers
    for x in invites:
        await bot.request_accept_invite(x['id'])    # Accept all invites
    await bot.main_sub("subscription/me-group-invite-create/" + str(bot.user_id), on_invited) # Subscribe to know when we get invited
    await bot.create_console(457468463)             # Start the connection to the server 457468463's console
    for x in await bot.get_active_consoles():       # For every server we have opened with (bot.create_console)
        await bot.console_sub("PlayerKilled", on_playerkilled, server_id=x)         # Subscribe to when players get killed. Execute on_playerkilled when someone dies.
        await bot.console_sub("PlayerMovedChunk", on_playermove, server_id=x)       # Subscribe to when players move chunks. Execute on_playermove when someone moves chunks.
    print(await bot.get_console_subs())                                             # Just shows what we have for current subscriptions
    while True:
        await asyncio.sleep(1)  # Sleep forever and let the bot do it's thing

asyncio.run(main())