<?php
/**
 * Created by JetBrains PhpStorm.
 * Author: Fox foxkeys@gmail.com
 * Date Time: 04.02.2013 14:58
 */

# Admin Utility
/*
ToDo
STAT_FTP_ADDR = '' # 'host user password'
BACKUPS_FTP_ADDR = ''

import os, sys, time, ftplib, zipfile
from subprocess import Popen, PIPE
*/
# set current dir
//ToDo os.chdir(os.path.dirname(os.path.abspath(__file__)))

//ToDo sys.path.insert(0, 'lib')

//ToDo import conf
//ToDo from igeclient.IClient import IClient

class Client extends IClient{
	function __construct( $login, $password, $server_addr = conf::SERVER_ADDR_LOCAL) {
		parent::__construct( $server_addr, null, array( $this, 'msgHandler' ), null, 'IClient/osc' );
		$this->connect( $login );
		$this->login( 'Alpha', $login, $password );
	}

	function msgHandler($id, $data){
		return;
	}
}
/*
class AdminClient(Client):
	def __init__(self):
		login = 'admin'
		password = open('var/token').read()
		Client.__init__(self, login, password)

def init():
	os.system('python run.py --reset')

pid_file = 'var/server.pid'

def start():
	if os.path.exists(pid_file):
		pid = open(pid_file).read()
		ps = Popen('ps -p ' + pid, shell=True, stdout=PIPE).stdout.read()
		if pid in ps:
			return # server running
		os.remove(pid_file)
	os.system('python run.py --silent --upgrade &')

def stop():
	pid = open(pid_file).read()
	os.system('kill -s TERM ' + pid)

def turn(n=1):
	client = AdminClient()
	for i in xrange(n):
		client.processTurn()
	client.exportAccounts()
	client.logout()

	# make players stat
	stat_dir = 'website/Alpha/'
	accounts = []
	for s in open('var/accounts.txt'):
		name, login, password, email, date = s.strip().split('\t')
		tm = time.strptime(date, '%d.%m.%y %H:%M')
		accounts.append((tm, date, name))
	accounts.sort(reverse=True)
	ps = open(stat_dir + 'players.html', 'w')
	print >> ps, '<table border=1>'
	for i, (tm, date, name) in enumerate(accounts[:101]):
		print >> ps, '<tr><td>%(i)i</td><td>%(date)s</td><td>%(name)s</td></tr>' \
			% vars()
	print >> ps, '</table>'
	ps.close()

	# upload stat on site
	if STAT_FTP_ADDR:
		host, user, password = STAT_FTP_ADDR.split()
		ftp = ftplib.FTP(host, user, password)
		ftp.cwd('/farspace/stat/Alpha/')
		for fname in os.listdir(stat_dir):
			ftp.storbinary('STOR %s' % fname, open(stat_dir + fname, 'rb'))
		ftp.quit()

def backup():
	backups_dir = 'var/backups/'
	try:
		os.makedirs(backups_dir)
	except:
		pass
	date_str = time.strftime('%y%m%d')

	client = AdminClient()
	client.backup(backups_dir + date_str)
	client.logout()

	# createFromDB archive
	zip_fname = date_str + '_backup.zip'
	zip = zipfile.ZipFile(backups_dir + zip_fname, 'w', zipfile.ZIP_DEFLATED)
	for fname in os.listdir(backups_dir):
		if fname.endswith('.osbackup'):
			zip.write(backups_dir + fname, fname)
			os.remove(backups_dir + fname)
	zip.close()

	# upload backup
	if BACKUPS_FTP_ADDR:
		host, user, password = BACKUPS_FTP_ADDR.split()
		ftp = ftplib.FTP(host, user, password)
		ftp.cwd('backups')
		ftp.storbinary('STOR ' + zip_fname, open(backups_dir + zip_fname, 'rb'))
		ftp.quit()

def touch():
	touch_file = 'var/touch.csv'
	if os.path.exists(touch_file):
		for s in open(touch_file):
			login, password = s.strip().split(';')
			client = Client(login, password)
			client.logout()

def create_account(login, password, nick, email):
	client = AdminClient()
	client.createAccount(login, password, nick, email)
	client.logout()

def create_test_account():
	create_account('test', 'test', 'test', 'test@farspace.ru')

def show_players():
	client = AdminClient()
	universe = client.getInfo(1)
	players = [(id, client.getInfo(id)) for id in universe.players]
	client.logout()
	for id, player in players:
		print id, player.name

def show_galaxies():
	client = AdminClient()
	universe = client.getInfo(1)
	galaxies = [(id, client.getInfo(id)) for id in universe.galaxies]
	client.logout()
	for id, galaxy in galaxies:
		print id, galaxy.name

# parse options
from optparse import OptionParser

parser = OptionParser()

parser.add_option('--init', action='store_true', dest='init',
	help='initialize server')
parser.add_option('--start', action='store_true', dest='start',
	help='start server')
parser.add_option('--stop', action='store_true', dest='stop',
	help='stop server')
parser.add_option('--turn', action='store_true', dest='turn',
	help='process single turn')
parser.add_option('--turns', type='int', dest='turns', metavar='N',
	help='process many turns')
parser.add_option('--backup', action='store_true', dest='backup',
	help='make backup')
parser.add_option('--touch', action='store_true', dest='touch',
	help='touch inactive players')
parser.add_option('--createFromDB-account', dest='create_account',
	metavar='login:password:nick:e-mail', help='createFromDB account')
parser.add_option('--createFromDB-test-account', action='store_true',
	dest='create_test_account', help='createFromDB test account')
parser.add_option('--show-players', action='store_true', dest='show_players',
	help='show players')
parser.add_option('--show-galaxies', action='store_true', dest='show_galaxies',
	help='show galaxies')

options, args = parser.parse_args()

if options.init:
	init()
elif options.start:
	start()
elif options.stop:
	stop()
elif options.turn:
	turn()
elif options.turns:
	turn(options.turns)
elif options.backup:
	backup()
elif options.touch:
	touch()
elif options.create_account:
	create_account(*options.create_account.split(':'))
elif options.create_test_account:
	create_test_account()
elif options.show_players:
	show_players()
elif options.show_galaxies:
	show_galaxies()
else:
	parser.print_help()
*/